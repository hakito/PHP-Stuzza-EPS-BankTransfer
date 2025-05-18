<?php

namespace at\externet\eps_bank_transfer;

use WpOrg\Requests\Exception\Transport;
use WpOrg\Requests\Requests;

/**
 * Handles the communication with the EPS scheme operator
 */
class SoCommunicator
{
    const TEST_MODE_URL = 'https://routing-test.eps.or.at/appl/epsSO';
    const LIVE_MODE_URL = 'https://routing.eps.or.at/appl/epsSO';

    /**
     * Optional function to send log messages to
     * @var callable
     */
    public $LogCallback;

    /**
     * requests transport
     * @internal
     * @var Transport
     */
    public $Transport;

    /**
     * Number of hash chars to append to RemittanceIdentifier.
     * If set greater as 0 you'll also have to set a ObscuritySeed
     * @var int
     */
    public $ObscuritySuffixLength = 0;

    /**
     * Seed to be used by hash function for RemittanceIdentifier
     * @var string
     */
    public $ObscuritySeed;

    /**
     * The base url SoCommunicator sends requests to
     * Defaults to SoCommunicator::LIVE_MODE_URL when constructor is called with $testMode == false
     * Defaults to SoCommunicator::TEST_MODE_URL when constructor is called with $testMode == true
     */
    public $BaseUrl;

    /**
     * Creates new Instance of SoCommunicator
     */
    public function __construct($testMode = false)
    {
        $this->BaseUrl = $testMode ? self::TEST_MODE_URL : self::LIVE_MODE_URL;
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
     * @throws XmlValidationException when the returned BankList does not validate against XSD
     * @return array of banks with bank name as key. The values are arrays with: bic, bezeichnung, land, epsUrl
     */
    public function GetBanksArray()
    {
        $xmlBanks = new \SimpleXMLElement($this->GetBanks(true));
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
     * @throws XmlValidationException when the returned BankList does not validate against XSD and $validateXSD is set to TRUE
     * @return string
     */
    public function GetBanks($validateXml = true)
    {
        $url = $this->BaseUrl . '/data/haendler/v2_6';
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
     * @throws \UnexpectedValueException when using security suffix without security seed
     * @return string BankResponseDetails
     */
    public function SendTransferInitiatorDetails($transferInitiatorDetails, $targetUrl = null)
    {
        if ($transferInitiatorDetails->RemittanceIdentifier != null)
            $transferInitiatorDetails->RemittanceIdentifier = $this->AppendHash($transferInitiatorDetails->RemittanceIdentifier);

        if ($transferInitiatorDetails->UnstructuredRemittanceIdentifier != null)
            $transferInitiatorDetails->UnstructuredRemittanceIdentifier = $this->AppendHash($transferInitiatorDetails->UnstructuredRemittanceIdentifier);

        if ($targetUrl === null)
            $targetUrl = $this->BaseUrl . '/transinit/eps/v2_6';

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
     * Will be called with the raw post data as first parameter and an Instance of
     * BankConfirmationDetails as second parameter. This callable must return TRUE.
     * @param callable $vitalityCheckCallback an optional callable for the vitalityCheck
     * Will be called with the raw post data as first parameter and an Instance of
     * VitalityCheckDetails as second parameter. This callable must return TRUE.
     * @param string $rawPostStream will read from this stream or file with file_get_contents
     * @param string $outputStream will write to this stream the expected responses for the
     * Scheme Operator
     * @throws InvalidCallbackException when callback is not callable
     * @throws CallbackResponseException when callback does not return TRUE
     * @throws XmlValidationException when $rawInputStream does not validate against XSD
     * @throws \UnexpectedValueException when using security suffix without security seed
     * @throws UnknownRemittanceIdentifierException when security suffix does not match
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
                $this->WriteLog('Vitality Check');
                if ($vitalityCheckCallback != null)
                {
                    $VitalityCheckDetails = new VitalityCheckDetails($xml);
                    $this->ConfirmationUrlCallback($vitalityCheckCallback, 'vitality check', array($HTTP_RAW_POST_DATA, $VitalityCheckDetails));
                }

                // 7.1.9 Schritt III-3: Bestätigung Vitality Check Händler-eps SO
                file_put_contents($outputStream, $HTTP_RAW_POST_DATA);
            }
            else if ($firstChildName == 'BankConfirmationDetails')
            {
                $this->WriteLog('Bank Confirmation');
                $BankConfirmationDetails = new BankConfirmationDetails($xml);

                // Strip security hash from remittance identifier
                $BankConfirmationDetails->SetRemittanceIdentifier($this->StripHash($BankConfirmationDetails->GetRemittanceIdentifier()));

                $shopResponseDetails->SessionId = $BankConfirmationDetails->GetSessionId();
                $shopResponseDetails->StatusCode = $BankConfirmationDetails->GetStatusCode();
                $shopResponseDetails->PaymentReferenceIdentifier = $BankConfirmationDetails->GetPaymentReferenceIdentifier();

                $this->WriteLog(sprintf('Calling confirmationUrlCallback for remittance identifier "%s" with status code %s', $BankConfirmationDetails->GetRemittanceIdentifier(), $BankConfirmationDetails->GetStatusCode()));
                $this->ConfirmationUrlCallback($confirmationCallback, 'confirmation', array($HTTP_RAW_POST_DATA, $BankConfirmationDetails));

                // Schritt III-8: Bestätigung Erhalt eps Zahlungsbestätigung Händler-eps SO
                $this->WriteLog('III-8 Confirming payment receipt');
                file_put_contents($outputStream, $shopResponseDetails->GetSimpleXml()->asXml());
            }
        }
        catch (\Exception $e)
        {
            $this->WriteLog($e->getMessage());

            if (is_subclass_of($e, 'at\externet\eps_bank_transfer\ShopResponseException'))
                $shopResponseDetails->ErrorMsg = $e->GetShopResponseErrorMessage();
            else
                $shopResponseDetails->ErrorMsg = 'An exception of type "' . get_class($e) . '" occurred during handling of the confirmation url';

            file_put_contents($outputStream, $shopResponseDetails->GetSimpleXml()->asXml());

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

    /**
     * @param $url target url
     * @param $logMessage log message
     * @return string response body
     * @throws HttpResponseException if returned status code is not 200
     */
    private function GetUrl($url, $logMessage)
    {
        $this->WriteLog($logMessage);
        $options = $this->Transport === null ? array() : array(
            'transport' => $this->Transport
        );
        $response = Requests::get($url, array(), $options);
        if ($response->status_code != 200)
        {
            $this->WriteLog($logMessage, false);
            throw new HttpResponseException('Could not load document. Server returned code: ' . $response->status_code);
        }
        $this->WriteLog($logMessage, true);
        return $response->body;
    }

    /**
     * @param $url target url
     * @param $data post parameters
     * @param $message log message
     * @return string response body
     * @throws HttpResponseException if returned status code is not 200
     */
    private function PostUrl($url, $data, $message)
    {
        $this->WriteLog($message);
        $options = $this->Transport === null ? array() : array(
            'transport' => $this->Transport
        );
        $response = Requests::post($url, array('Content-Type' => 'text/xml; charset=UTF-8'), $data, $options);

        if ($response->status_code != 200)
        {
            $this->WriteLog($message, false);
            throw new HttpResponseException('Could not load document. Server returned code: ' . $response->status_code);
        }

        $this->WriteLog($message, true);
        return $response->body;
    }

    private function WriteLog($message, $success = null)
    {
        if (is_callable($this->LogCallback))
        {
            if ($success !== null)
                $message = ($success ? "SUCCESS:" : "FAILED:") . ' ' . $message;

            call_user_func($this->LogCallback, $message);
        }
    }

    private function AppendHash($string)
    {
        if ($this->ObscuritySuffixLength == 0)
            return $string;

        if (empty($this->ObscuritySeed))
                throw new \UnexpectedValueException('No security seed set when using security suffix.');

        $hash = base64_encode(crypt($string, $this->ObscuritySeed));
        return $string . substr($hash, 0, $this->ObscuritySuffixLength);
    }

    private function StripHash($suffixed)
    {
        if ($this->ObscuritySuffixLength == 0)
            return $suffixed;

        $remittanceIdentifier = substr($suffixed, 0, -$this->ObscuritySuffixLength);
        if ($this->AppendHash($remittanceIdentifier) != $suffixed)
            throw new UnknownRemittanceIdentifierException('Unknown RemittanceIdentifier supplied: ' . $suffixed);

        return $remittanceIdentifier;
    }
}
