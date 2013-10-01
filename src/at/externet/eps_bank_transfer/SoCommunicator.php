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
     * @param callable $callback a callable to send BankConfirmationDetails to.
     * This callable must return TRUE.
     * @param string $rawPostStream will read from this stream or file with file_get_contents
     * @throws InvalidCallbackException when callback is not callable
     * @throws CallbackResponseException when callback does not return TRUE
     * @throws XmlValidationException when $rawInputStream does not validate against XSD
     * @throws cakephp\SocketException when communication with SO fails
     */
    public function HandleConfirmationUrl($callback, $rawPostStream = 'php://input')
    {
        if (!is_callable($callback))
        {
            $message = 'The given callback function is not a callable';
            $this->WriteLog('Cannot handle confirmation URL. ' . $message);
            throw new InvalidCallbackException($message);
        }

        $HTTP_RAW_POST_DATA = file_get_contents($rawPostStream);
        XmlValidator::ValidateEpsProtocol($HTTP_RAW_POST_DATA);

        if (!call_user_func($callback, $HTTP_RAW_POST_DATA))
        {
            $message = 'The given callback function did not return TRUE';
            $this->WriteLog('Cannot handle confirmation URL. ' . $message);
            throw new CallbackResponseException($message);
        }

        // TODO 7.1.8. Schritt III-2: Vitality Check eps SO-Händler
        // TODO 7.1.9. Schritt III-3: Bestätigung Vitality Check Händler-eps SO
        // TOOD Schritt III-8: Bestätigung Erhalt eps Zahlungsbestätigung Händler-eps SO
        //$this->GetBankConfirmationDetailsArray();
    }

    /*


      public function GetBankConfirmationDetails()
      {
      $HTTP_RAW_POST_DATA = file_get_contents($this->RawPostStream);
      XmlValidator::ValidateEpsProtocol($HTTP_RAW_POST_DATA);
      return $HTTP_RAW_POST_DATA;
      }
     */

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