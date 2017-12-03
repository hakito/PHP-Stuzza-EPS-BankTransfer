<?php

namespace at\externet\eps_bank_transfer;

class BankConfirmationDetails
{

    /** @var \SimpleXMLElement */
    public $simpleXml;

    private $remittanceIdentifier;
    private $paymentReferenceIdentifier;
    private $referenceIdentifier;
    private $orderingCustomerNameAddress;
    private $orderingCustomerIdentifier;
    private $orderingCustomerBIC;
    private $sessionId;
    private $statusCode;

    public function __construct($simpleXml)
    {
        $this->simpleXml = $simpleXml;
        $this->init($this->simpleXml);
    }

    /**
     *
     * @param \SimpleXMLElement $simpleXml
     */
    private function init($simpleXml)
    {
        $epspChildren = $simpleXml->children(XMLNS_epsp);
        $BankConfirmationDetails = $epspChildren[0];
        $t1 = $BankConfirmationDetails->children(XMLNS_eps); // Necessary because of missing language feature in PHP 5.3
        $PaymentConfirmationDetails = $t1[0];
        $t2 = $PaymentConfirmationDetails->children(XMLNS_epi);
        $this->remittanceIdentifier = null;

        $this->SetPaymentReferenceIdentifier($PaymentConfirmationDetails->PaymentReferenceIdentifier);
        $this->SetSessionId($BankConfirmationDetails->SessionId);
        $this->SetStatusCode($PaymentConfirmationDetails->StatusCode);

        if (isset($t2->RemittanceIdentifier))
        {
            $this->SetRemittanceIdentifier($t2->RemittanceIdentifier);
        }
        elseif (isset($t2->UnstructuredRemittanceIdentifier))
        {
            $this->SetRemittanceIdentifier($t2->UnstructuredRemittanceIdentifier);
        }
        else
        {
            $t3 = $PaymentConfirmationDetails->PaymentInitiatorDetails->children(XMLNS_epi);
            $EpiDetails = $t3[0];
            $t4 = $EpiDetails->PaymentInstructionDetails;
            if (isset($t4->RemittanceIdentifier))
            {
                $this->SetRemittanceIdentifier($t4->RemittanceIdentifier);
            }
            else
            {
                $this->SetRemittanceIdentifier($t4->UnstructuredRemittanceIdentifier);
            }

            // ReferenceIdentifier used in TransferInitiatorDetails as $internalReferenceId
            $t5 = $EpiDetails->IdentificationDetails;
            $this->SetReferenceIdentifier($t5->ReferenceIdentifier);

            //The following 3 data elements could be used e.g. for routing information within additional business scenarios (e.g. EBPP).
            //These are optional data elements
            if(isset($t5->OrderingCustomerNameAddressText)){
                $this->SetOrderingCustomerNameAddressText($t5->OrderingCustomerNameAddressText);
            }

            if(isset($t5->OrderingCustomerIdentifier)){
                $this->SetOrderingCustomerIdentifier($t5->OrderingCustomerIdentifier);
            }

            if(isset($t5->OrderingCustomerOfiIdentifier)){
                $this->SetOrderingCustomerBIC($t5->OrderingCustomerOfiIdentifier);
            }
        }

        if ($this->remittanceIdentifier == null)
            throw new \LogicException('Could not find RemittanceIdentifier in XML');
    }

    public function SetRemittanceIdentifier($a)
    {
        $this->remittanceIdentifier = (string) $a;
    }

    /**
     * Gets epi:RemittanceIdentifier or epi:UnstructuredRemittanceIdentifier - depending on which one is present in the XML file
     */
    public function GetRemittanceIdentifier()
    {
        return $this->remittanceIdentifier;
    }

    public function SetPaymentReferenceIdentifier($a)
    {
        $this->paymentReferenceIdentifier = (string) $a;
    }

    /**
     * Die von der Bank generierte Ersterfasserreferenz
     * @return string
     */
    public function GetPaymentReferenceIdentifier()
    {
        return $this->paymentReferenceIdentifier;
    }


    public function SetReferenceIdentifier($a)
    {
        $this->referenceIdentifier = (string) $a;
    }

    public function GetReferenceIdentifier()
    {
        return $this->referenceIdentifier;
    }


    /**
     * Set the identification of ordering customer (name and/or address of the buyer) in non-coded form
     * @param $customerName
     */
    public function SetOrderingCustomerNameAddressText($customerName)
    {
        $this->orderingCustomerNameAddress = (string) $customerName;
    }

    /**
     * Get the identification of ordering customer (name and/or address of the buyer) in non-coded form
     * @return mixed
     */
    public function GetOrderingCustomerNameAddress()
    {
        return $this->orderingCustomerNameAddress;
    }


    /**
     * Set the identification of the customer’s (buyer’s) account identification by e.g. IBAN, BBAN, etc.
     * @param $iban
     */
    public function SetOrderingCustomerIdentifier($iban){
        $this->orderingCustomerIdentifier = (string) $iban;
    }

    /**
     * Get the identification of the customer’s (buyer’s) account identification by e.g. IBAN, BBAN, etc.
     * @return mixed
     */
    public function GetOrderingCustomerIdentifier(){
        return $this->orderingCustomerIdentifier;
    }


    /**
     * Set the identification of the customer’s (buyer’s) financial institution by a BIC
     * @param $bic
     */
    public function SetOrderingCustomerBIC($bic){
        $this->orderingCustomerBIC = (string) $bic;
    }

    /**
     * Get the identification of the customer’s (buyer’s) financial institution by a BIC
     * @return mixed
     */
    public function GetOrderingCustomerBIC(){
        return $this->orderingCustomerBIC;
    }



    public function SetSessionId($a)
    {
        $this->sessionId = (string) $a;
    }

    /**
     * Die von der Bank generierte Session Kennung
     * @return string
     */
    public function GetSessionId()
    {
        return $this->sessionId;
    }

    public function SetStatusCode($a)
    {
        $this->statusCode = (string) $a;
    }

    /**
     * Status Code
     * @return string
     */
    public function GetStatusCode()
    {
        return $this->statusCode;
    }
}
