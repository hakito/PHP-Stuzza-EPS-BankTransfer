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
     * This callable must return TRUE.
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
        $this->TestCallability($confirmationCallback, 'confirmationCallback', $outputStream);
        if ($vitalityCheckCallback != null)
            $this->TestCallability($vitalityCheckCallback, 'vitalityCheckCallback', $outputStream);

        $HTTP_RAW_POST_DATA = file_get_contents($rawPostStream);        
        try
        {
            XmlValidator::ValidateEpsProtocol($HTTP_RAW_POST_DATA);
        }
        catch (\Exception $e)
        {
            $this->ReturnShopResponseError('Error occured during XML validation', $outputStream);
            throw $e;
        }        
        
        $xml = new \SimpleXMLElement($HTTP_RAW_POST_DATA);
        $epspChildren = $xml->children(XMLNS_epsp);
        $firstChildName = $epspChildren[0]->getName();
        
        if ($firstChildName == 'VitalityCheckDetails')
        {            
            if ($vitalityCheckCallback != null)            
            {
                $this->ConfirmationUrlCallback($vitalityCheckCallback, 'vitality check', $HTTP_RAW_POST_DATA, $outputStream);
            }
            // 7.1.9 Schritt III-3: Bestätigung Vitality Check Händler-eps SO
            file_put_contents($outputStream, $HTTP_RAW_POST_DATA);
        } else if ($firstChildName == 'BankConfirmationDetails')
        {
            $this->ConfirmationUrlCallback($confirmationCallback, 'confirmation', $HTTP_RAW_POST_DATA, $outputStream);

            // Schritt III-8: Bestätigung Erhalt eps Zahlungsbestätigung Händler-eps SO
            $BankConfirmationDetails = $epspChildren[0];
            $t = $BankConfirmationDetails->children(XMLNS_eps); // Nescessary because of missing language feature in PHP 5.3
            $PaymentConfirmationDetails = $t[0];

            $shopResponseDetails = new ShopResponseDetails();
            $shopResponseDetails->SessionId = $BankConfirmationDetails->SessionId;
            $shopResponseDetails->StatusCode = $PaymentConfirmationDetails->StatusCode;
            if (!empty($PaymentConfirmationDetails->PaymentReferenceIdentifier))
                $shopResponseDetails->PaymentReferenceIdentifier = $PaymentConfirmationDetails->PaymentReferenceIdentifier;
            file_put_contents($outputStream, $shopResponseDetails->GetSimpleXml()->asXml());            
        } else
        {        
            // Should never be executed
            $message = 'No implementation to handle the given value: ' . $firstChildName;
            $this->ReturnShopResponseError($message, $outputStream);
            throw new \UnexpectedValueException($message);
        }
    }
    
    // Private functions

    private function ReturnShopResponseError($message, $outputStream)
    {            
        $shopResponseDetails = new ShopResponseDetails();        
        $shopResponseDetails->ErrorMsg = $message;
        file_put_contents($outputStream, $shopResponseDetails->GetSimpleXml()->asXml());
        $this->WriteLog($message);        
    }
    
    private function ConfirmationUrlCallback($callback, $name, $xml, $outputStream)
    {
        if (call_user_func($callback, $xml) !== true)
        {
            $message = 'The given ' . $name . ' confirmation callback function did not return TRUE';
            $fullMessage = 'Cannot handle confirmation URL. ' . $message;
            $this->ReturnShopResponseError($fullMessage, $outputStream);
            throw new CallbackResponseException($message);
        }
    }
    
    private function TestCallability(&$callback, $name, $outputStream)
    {
        if (!is_callable($callback))
        {
            $message = 'The given callback function for "' . $name . '" is not a callable';            
            $fullMessage = 'Cannot handle confirmation URL. ' . $message;                        
            $this->ReturnShopResponseError($fullMessage, $outputStream);
            throw new InvalidCallbackException($message);
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