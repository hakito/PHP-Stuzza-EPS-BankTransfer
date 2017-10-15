<?php

namespace at\externet\eps_bank_transfer;

class XmlValidatorTest extends BaseTest
{
    /** @var \at\externet\eps_bank_transfer\XmlValidator validator */
    public $target;

    public function setUp()
    {
        parent::setUp();
        $this->target = new XmlValidator();
    }

    public function testBanksThrowsExceptionOnEmptyData()
    {
        $this->setExpectedException('at\externet\eps_bank_transfer\XmlValidationException');
        XmlValidator::ValidateBankList('');
    }

    public function testBanksThrowsExceptionOnInvalidData()
    {
        $this->setExpectedException('at\externet\eps_bank_transfer\XmlValidationException');
        XmlValidator::ValidateBankList('bar');
    }

    public function testBanksThrowsExceptionOnInvalidXml()
    {
        $this->setExpectedException('at\externet\eps_bank_transfer\XmlValidationException');
        XmlValidator::ValidateBankList($this->GetEpsData('BankListInvalid.xml'));
    }

    public function testBanksReturnsXmlString()
    {
        $ret = XmlValidator::ValidateBankList($this->GetEpsData('BankListSample.xml'));
        $this->assertTrue($ret);
    }
    
    public function testWithSignatureReturnsTrue()
    {
        $ret = XmlValidator::ValidateEpsProtocol($this->GetEpsData('BankConfirmationDetailsWithSignature.xml'));
        $this->assertTrue($ret);
    }
}
