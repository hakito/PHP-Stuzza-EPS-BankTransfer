<?php

namespace at\externet\eps_bank_transfer;

use org\cakephp;

class SoCommunicator
{

    /** @var callable function to send log messages to */
    public $LogCallback;

    /** @var cakephp\HttpSocket http socket */
    public $HttpSocket;

    public function __construct()
    {
        $this->HttpSocket = new cakephp\HttpSocket();
    }

    /**
     * Failsafe version of GetBanksArray(). All Exceptions will be swallowed
     * @return null or result of GetBanksArray()
     */
    public function TryGetBanksArray()
    {
        try
        {
            return $this->GetBanksArray();
        }
        catch (\Exception $e)
        {
            $this->WriteLog('Could not get Bank Array. ' . $e);
            return null;
        }
    }

    /**
     * Get associative array of banks from Scheme Operator. The bank name (bezeichnung)
     * will be used as key.
     * @throws cakephp\SocketException when communication with SO fails
     * @throws XmlValidationException when the returned BankList does not validate against XSD
     * @return array of banks
     */
    public function GetBanksArray()
    {
        $xmlBanks = new \SimpleXMLElement($this->GetBanks());
        $banks = array();
        foreach ($xmlBanks as $xmlBank)
        {
            $bezeichnung = '' . $xmlBank->bezeichnung;
            $banks[$bezeichnung] = array(
                'bic' => '' . $xmlBank->bic,
                'bezeichnung' => $bezeichnung,
                'land' => '' . $xmlBank->land,
                'epsUrl' => '' . $xmlBank->epsUrl,
            );
        }
        return $banks;
    }

    /**
     * Get XML of banks from scheme operator.
     * Will throw an exception if data cannot be fetched, or XSD validation fails.
     * @param bool $validateXml validate against XSD
     * @throws cakephp\SocketException when communication with SO fails
     * @throws XmlValidationException when the returned BankList does not validate against XSD and $validateXSD is set to TRUE
     * @return string
     */
    public function GetBanks($validateXml = true)
    {
        $url = 'https://routing.eps.or.at/appl/epsSO/data/haendler/v2_4';
        $body = $this->GetUrl($url, 'Requesting bank list');

        if ($validateXml)
            XmlValidator::ValidateBankList($body);
        return $body;
    }

    /**
     * Sends the given TransferInitiatorDetails to the Scheme Operator
     * @param TransferInitiatorDetails $transferInitiatorDetails
     * @param string $targetUrl url with preselected bank identifier
     * @throws XmlValidationException when the returned BankResponseDetails does not validate against XSD
     * @throws cakephp\SocketException when communication with SO fails
     * @return string BankResponseDetails
     */
    public function SendTransferInitiatorDetails($transferInitiatorDetails, $targetUrl = null)
    {
        if ($targetUrl == null)
            $targetUrl = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_4';

        $data = $transferInitiatorDetails->GetSimpleXml();
        $xmlData = $data->asXML();
        $response = $this->PostUrl($targetUrl, $xmlData, 'Send payment order');

        XmlValidator::ValidateEpsProtocol($response);
        return $response;
    }

    /**
     * Call this function when the confirmation URL is called by the Scheme Operator.
     * The function will write ShopResponseDetails to the $outputStream in case of
     * BankConfirmationDetails.
     * 
     * @param callable $confirmationCallback a callable to send BankConfirmationDetails to.
     * Will be called with the parameters BankConfirmationDetails as string and 
     * RemittanceIdentifier as string. This callable must return TRUE.
     * @param callable $vitalityCheckCallback an optional callable for the vitalityCheck
     * @param string $rawPostStream will read from this stream or file with file_get_contents
     * @param string $outputStream will write to this stream the expected responses for the
     * Scheme Operator
     * @throws InvalidCallbackException when callback is not callable
     * @throws CallbackResponseException when callback does not return TRUE
     * @throws XmlValidationException when $rawInputStream does not validate against XSD
     * @throws cakephp\SocketException when communication with SO fails
     */
    public function HandleConfirmationUrl($confirmationCallback, $vitalityCheckCallback = null, $rawPostStream = 'php://input', $outputStream = 'php://output')
    {
        $shopResponseDetails = new ShopResponseDetails();
        try
        {
            $this->TestCallability($confirmationCallback, 'confirmationCallback');
            if ($vitalityCheckCallback != null)
                $this->TestCallability($vitalityCheckCallback, 'vitalityCheckCallback');

            $HTTP_RAW_POST_DATA = file_get_contents($rawPostStream);
            XmlValidator::ValidateEpsProtocol($HTTP_RAW_POST_DATA);

            $xml = new \SimpleXMLElement($HTTP_RAW_POST_DATA);
            $epspChildren = $xml->children(XMLNS_epsp);
            $firstChildName = $epspChildren[0]->getName();

            if ($firstChildName == 'VitalityCheckDetails')
            {
                if ($vitalityCheckCallback != null)
                {
                    $this->ConfirmationUrlCallback($vitalityCheckCallback, 'vitality check', array($HTTP_RAW_POST_DATA));
                }
                // 7.1.9 Schritt III-3: Bestätigung Vitality Check Händler-eps SO
                file_put_contents($outputStream, $HTTP_RAW_POST_DATA);
            }
            else if ($firstChildName == 'BankConfirmationDetails')
            {
                $BankConfirmationDetails = $epspChildren[0];
                $t1 = $BankConfirmationDetails->children(XMLNS_eps); // Nescessary because of missing language feature in PHP 5.3
                $PaymentConfirmationDetails = $t1[0];
                $t2 = $PaymentConfirmationDetails->children(XMLNS_epi);
                $remittanceIdentifier = null;

                if (isset($t2->RemittanceIdentifier))
                {
                    $remittanceIdentifier = $t2->RemittanceIdentifier;
                }
                else
                {
                    $t3 = $PaymentConfirmationDetails->PaymentInitiatorDetails->children(XMLNS_epi);
                    $EpiDetails = $t3[0];
                    $remittanceIdentifier = $EpiDetails->PaymentInstructionDetails->RemittanceIdentifier;
                }

                if ($remittanceIdentifier == null)
                {
                    $message = 'Could not find RemittanceIdentifier in XML';
                    throw new \LogicException($message);
                }

                $this->ConfirmationUrlCallback($confirmationCallback, 'confirmation', array($HTTP_RAW_POST_DATA, $remittanceIdentifier));

                // Schritt III-8: Bestätigung Erhalt eps Zahlungsbestätigung Händler-eps SO
                $shopResponseDetails->SessionId = $BankConfirmationDetails->SessionId;
                $shopResponseDetails->StatusCode = $PaymentConfirmationDetails->StatusCode;
                if (!empty($PaymentConfirmationDetails->PaymentReferenceIdentifier))
                    $shopResponseDetails->PaymentReferenceIdentifier = $PaymentConfirmationDetails->PaymentReferenceIdentifier;
                file_put_contents($outputStream, $shopResponseDetails->GetSimpleXml()->asXml());
            } else
            {
                // Should never be executed
                $message = 'No implementation to handle the given value: ' . $firstChildName;        
                throw new \UnexpectedValueException($message);;
            }
        }
        catch (\Exception $e)
        {
            if (is_subclass_of($e, 'at\externet\eps_bank_transfer\ShopResponseException'))            
                $shopResponseDetails->ErrorMsg = $e->GetShopResponseErrorMessage();
            else
                $shopResponseDetails->ErrorMsg = 'An exception of type "' . get_class($e) . '" occurred during handling of the confirmation url';           
            
            file_put_contents($outputStream, $shopResponseDetails->GetSimpleXml()->asXml());

            $this->WriteLog($e->getMessage());
            throw $e;
        }
    }
    
    // Private functions
   
    private function ConfirmationUrlCallback($callback, $name, $args)
    {
        if (call_user_func_array($callback, $args) !== true)
        {
            $message = 'The given ' . $name . ' confirmation callback function did not return TRUE';
            $fullMessage = 'Cannot handle confirmation URL. ' . $message;
            throw new CallbackResponseException($fullMessage);
        }
    }
    
    private function TestCallability(&$callback, $name)
    {
        if (!is_callable($callback))
        {
            $message = 'The given callback function for "' . $name . '" is not a callable';            
            $fullMessage = 'Cannot handle confirmation URL. ' . $message;                        
            throw new InvalidCallbackException($fullMessage);
        }
    }

    private function GetUrl($url, $logMessage)
    {
        $this->WriteLog($logMessage);
        $response = $this->HttpSocket->get($url);
        if ($response->code != 200)
        {
            $this->WriteLog($logMessage, false);
            throw new HttpResponseException('Could not load document. Server returned code: ' . $response->code);
        }
        $this->WriteLog($logMessage, true);
        return $response->body;
    }

    private function PostUrl($url, $data, $message)
    {
        $this->WriteLog($message);
        $response = $this->HttpSocket->post($url, $data, array('header' => array('Content-Type' => 'text/plain; charset=UTF-8')));

        if ($response->code != 200)
        {
            $this->WriteLog($message, false);
            throw new HttpResponseException('Could not load document. Server returned code: ' . $response->code);
        }

        $this->WriteLog($message, true);
        return $response;
    }

    private function WriteLog($message, $success = null)
    {
        if (is_callable($this->LogCallback))
        {
            if ($success != null)
                $message = ($success ? "SUCCESS" : "FAIL") . ' ' . $message;
            call_user_func($this->LogCallback, $message);
        }
    }

}