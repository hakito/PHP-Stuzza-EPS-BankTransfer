<?php

namespace at\externet\eps_bank_transfer;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'BaseTest.php';

class BankConfirmationDetailsTest extends BaseTest
{
    /** @var \SimpleXMLElement[] */
    public $simpleXmls;

    public function setUp()
    {
        parent::setUp();
        $this->simpleXmls = array();
        $this->simpleXmls['WithSignature']                = new \SimpleXMLElement($this->GetEpsData('BankConfirmationDetailsWithSignature.xml'));
        $this->simpleXmls['UnstructuredWithoutSignature'] = new \SimpleXMLElement($this->GetEpsData('BankConfirmationDetailsWithoutSignatureUnstructuredRemittanceIdentifier.xml'));
        $this->simpleXmls['WithoutSignature']             = new \SimpleXMLElement($this->GetEpsData('BankConfirmationDetailsWithoutSignature.xml'));
        $this->simpleXmls['UnstructuredWithSignature']    = new \SimpleXMLElement($this->GetEpsData('BankConfirmationDetailsWithSignatureUnstructuredRemittanceIdentifier.xml'));
    }

    public function testGetRemittanceIdentifier()
    {
        $t = new BankConfirmationDetails($this->simpleXmls['WithSignature']);
        $actual = $t->GetRemittanceIdentifier();

        $this->assertEquals('AT1234567890XYZ', $actual);
    }

   public function testGetRemittanceIdentifierWithUnstructuredRemittanceIdentifierWithoutSignature()
   {
        $t = new BankConfirmationDetails($this->simpleXmls['UnstructuredWithoutSignature']);
        $this->assertEquals('AT1234567890XYZ', $t->GetRemittanceIdentifier());
        $this->assertEquals('OK', $t->GetStatusCode());
    }

   public function testGetRemittanceIdentifierWithRemittanceIdentifierWithoutSignature()
   {
        $t = new BankConfirmationDetails($this->simpleXmls['WithoutSignature']);
        $this->assertEquals('AT1234567890XYZ', $t->GetRemittanceIdentifier());
        $this->assertEquals('OK', $t->GetStatusCode());
    }

    public function testGetRemittanceIdentifierWithUnstructuredRemittanceIdentifierWithGivenSignature()
    {
        $t = new BankConfirmationDetails($this->simpleXmls['UnstructuredWithSignature']);
        $this->assertEquals('AT1234567890XYZ', $t->GetRemittanceIdentifier());
        $this->assertEquals('OK', $t->GetStatusCode());        
    }

    public function testGetPaymentReferenceIdentifier()
    {
        $t = new BankConfirmationDetails($this->simpleXmls['WithSignature']);
        $actual = $t->GetPaymentReferenceIdentifier();
        $this->assertEquals('RIAT1234567890XYZ', $actual);
    }

    public function testGetSessionId()
    {
        $t = new BankConfirmationDetails($this->simpleXmls['WithSignature']);
        $actual = $t->GetSessionId();
        $this->assertEquals('String', $actual);
    }

    public function testGetStatusCode()
    {
        $t = new BankConfirmationDetails($this->simpleXmls['WithSignature']);
        $actual = $t->GetStatusCode();
        $this->assertEquals('OK', $actual);
    }

    public function testGetReferenceIdentifier()
    {
        $t = new BankConfirmationDetails($this->simpleXmls['WithSignature']);
        $actual = $t->GetReferenceIdentifier();
        $this->assertEquals('1234567890ABCDEFG', $actual);
    }
}