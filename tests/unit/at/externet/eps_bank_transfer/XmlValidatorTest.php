<?php

namespace at\externet\eps_bank_transfer;

class XmlValidatorTest extends BaseTest
{
    /** @var \at\externet\eps_bank_transfer\XmlValidator validator */
    public $target;

    protected function setUp(): void
    {
        parent::setUp();
        $this->target = new XmlValidator();
    }

    public function testBanksThrowsExceptionOnEmptyData()
    {
        $this->expectException(XmlValidationException::class);
        XmlValidator::ValidateBankList('');
    }

    public function testBanksThrowsExceptionOnInvalidData()
    {
        $this->expectException(XmlValidationException::class);
        XmlValidator::ValidateBankList('bar');
    }

    public function testBanksThrowsExceptionOnInvalidXml()
    {
        $this->expectException(XmlValidationException::class);
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

    public function testRefundResponseValid()
    {
        $ret = XmlValidator::ValidateEpsRefund($this->GetEpsData('RefundResponseAccepted000.xml'));
        $this->assertTrue($ret);
    }
}
