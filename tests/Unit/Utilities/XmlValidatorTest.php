<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Utilities;

use Psa\EpsBankTransfer\Exceptions\XmlValidationException;
use Psa\EpsBankTransfer\Tests\Helper\XmlFixtureTestTrait;
use Psa\EpsBankTransfer\Utilities\XmlValidator;
use PHPUnit\Framework\TestCase;

class XmlValidatorTest extends TestCase
{
    use XmlFixtureTestTrait;

    public function testBanksThrowsExceptionOnEmptyData(): void
    {
        $this->expectException(XmlValidationException::class);
        XmlValidator::validateBankList('');
    }

    public function testBanksThrowsExceptionOnInvalidData(): void
    {
        $this->expectException(XmlValidationException::class);
        XmlValidator::validateBankList('bar');
    }

    public function testBanksThrowsExceptionOnInvalidXml(): void
    {
        $this->expectException(XmlValidationException::class);
        XmlValidator::validateBankList($this->loadFixture('BankListInvalid.xml'));
    }

    /**
     * @throws XmlValidationException
     */
    public function testBanksReturnsXmlString(): void
    {
        $result = XmlValidator::validateBankList($this->loadFixture('BankListSample.xml'));
        $this->assertTrue($result);
    }

    /**
     * @throws XmlValidationException
     */
    public function testWithSignatureReturnsTrue(): void
    {
        $result = XmlValidator::validateEpsProtocol($this->loadFixture('V26/BankConfirmationDetailsWithSignature.xml'));
        $this->assertTrue($result);
    }

    /**
     * @throws XmlValidationException
     */
    public function testRefundResponseValid(): void
    {
        $result = XmlValidator::validateEpsRefund($this->loadFixture('V26/RefundResponseAccepted000.xml'));
        $this->assertTrue($result);
    }
}