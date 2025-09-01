<?php

namespace Psa\EpsBankTransfer\Tests\Requests;

use Exception;
use Psa\EpsBankTransfer\Exceptions\XmlValidationException;
use Psa\EpsBankTransfer\Requests\TransferInitiatorDetails;
use Psa\EpsBankTransfer\Requests\Parts\PaymentFlowUrls;
use Psa\EpsBankTransfer\Requests\Parts\WebshopArticle;
use Psa\EpsBankTransfer\Serializer\SerializerFactory;
use Psa\EpsBankTransfer\Tests\Helper\XmlFixtureTestTrait;
use Psa\EpsBankTransfer\Utilities\Fingerprint;
use Psa\EpsBankTransfer\Utilities\XmlValidator;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

class InitiateTransferRequestTest extends TestCase
{

    use XmlFixtureTestTrait;

    private const TEST_USER_ID = 'AKLJS231534';
    private const TEST_SECRET = 'topSecret';
    private const TEST_BIC = 'GAWIATW1XXX';
    private const TEST_NAME = 'Max Mustermann';
    private const TEST_ACCOUNT = 'AT611904300234573201';
    private const TEST_REFERENCE = '1234567890ABCDEFG';
    private const TEST_AMOUNT = 15000;
    private const TEST_DATE = '2007-03-16';
    private const TEST_CURRENCY = 'EUR';
    private const TEST_REMITTANCE_ID = 'AT1234567890XYZ';
    private const TEST_ARTICLE_NAME = 'Toaster';
    private const TEST_ARTICLE_COUNT = 1;
    private const TEST_OFI_IDENTIFIER = 'TESTBANKXXX';
    private const TEST_EXPIRATION_MINUTES = 5;
    private const TEST_UNSTRUCTURED_REMITTANCE = 'Foo is not Bar';

    /** @var PaymentFlowUrls */
    private $transferMsgDetails;

    /** @var SerializerInterface */
    private $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transferMsgDetails = $this->createTransferMsgDetails();
        $this->serializer = SerializerFactory::create();
    }

    /**
     * @throws Exception
     */
    public function testGenerateTransferInitiatorDetails()
    {
        $data = $this->createTransferInitiatorDetailsWithArticle();
        $xmlData = $this->serializer->serialize($data->toV26(), 'xml');
        XmlValidator::validateEpsProtocol($xmlData);
        $this->assertXmlEqualsFixture('TransferInitiatorDetailsWithoutSignature.xml', $xmlData);
    }

    /**
     * @throws Exception
     */
    public function testGenerateTransferInitiatorDetailsWithOfiIdentifier()
    {
        $data = $this->createTransferInitiatorDetailsWithArticle();
        $data->setOrderingCustomerOfiIdentifier(self::TEST_OFI_IDENTIFIER);

        $xmlData = $this->serializer->serialize($data->toV26(), 'xml');
        XmlValidator::validateEpsProtocol($xmlData);
        $this->assertXmlEqualsFixture('TransferInitiatorDetailsWithoutSignatureAndOrderingCustomerOfiIdentifier.xml', $xmlData);
    }

    /**
     * @throws Exception
     */
    public function testTransferInitiatorDetailsInvalidExpirationMinutes()
    {
        $data = $this->createTransferInitiatorDetails();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expiration minutes value of "3" is not between 5 and 60.');
        $data->setExpirationMinutes(3);
    }

    /**
     * @throws Exception
     */
    public function testTransferInitiatorDetailsWithExpirationTime()
    {
        $data = $this->createTransferInitiatorDetails();
        $data->setExpirationMinutes(self::TEST_EXPIRATION_MINUTES);
        $data->setRemittanceIdentifier('Order1');

        $epsProtocolDetails = $data->toV26();
        $xmlData = $this->serializer->serialize($epsProtocolDetails, 'xml');

        XmlValidator::validateEpsProtocol($xmlData);
        $this->assertStringContainsString('ExpirationTime', $xmlData);
    }

    /**
     * @throws XmlValidationException
     * @throws Exception
     */
    public function testTransferInitiatorDetailsWithUnstructuredRemittanceIdentifier()
    {
        $data = $this->createTransferInitiatorDetails();
        $data->setUnstructuredRemittanceIdentifier(self::TEST_UNSTRUCTURED_REMITTANCE);
        $data->setExpirationMinutes(self::TEST_EXPIRATION_MINUTES);
        $epsProtocolDetails = $data->toV26();

        $xmlData = $this->serializer->serialize($epsProtocolDetails, 'xml');

        XmlValidator::validateEpsProtocol($xmlData);
        $this->assertStringContainsString('<epi:UnstructuredRemittanceIdentifier>' . self::TEST_UNSTRUCTURED_REMITTANCE . '</epi:UnstructuredRemittanceIdentifier>', $xmlData);
    }

    public function testMD5FingerprintIsCalculatedCorrectly()
    {
        $data = $this->createTransferInitiatorDetails();
        $remittanceIdentifier = "remittanceIdentifier";
        $data->setRemittanceIdentifier($remittanceIdentifier);

        $actual = $data->getMD5Fingerprint();
        $expected = $this->calculateFingerprint($remittanceIdentifier);

        $this->assertEquals($expected, $actual, 'Expected MD5 Fingerprint to be equal');
    }

    public function testMD5FingerprintIsCalculatedCorrectlyWithAnUnstructuredRemittanceIdentifier()
    {
        $data = $this->createTransferInitiatorDetails();
        $unstructuredRemittanceIdentifier = 'unstructuredRemittanceIdentifier';
        $data->setUnstructuredRemittanceIdentifier($unstructuredRemittanceIdentifier);

        $actual = $data->getMD5Fingerprint();
        $expected = $this->calculateFingerprint($unstructuredRemittanceIdentifier);

        $this->assertEquals($expected, $actual, 'Expected MD5 Fingerprint to be equal');
    }

    private function createTransferMsgDetails(): PaymentFlowUrls
    {
        return new PaymentFlowUrls(
            "http://10.18.70.8:7001/vendorconfirmation",
            "http://10.18.70.8:7001/transactionok?danke.asp",
            "http://10.18.70.8:7001/transactionnok?fehler.asp"
        );
    }

    private function createTransferInitiatorDetails(): TransferInitiatorDetails
    {
        return new TransferInitiatorDetails(
            self::TEST_USER_ID,
            self::TEST_SECRET,
            self::TEST_BIC,
            self::TEST_NAME,
            self::TEST_ACCOUNT,
            self::TEST_REFERENCE,
            self::TEST_AMOUNT,
            $this->transferMsgDetails,
            self::TEST_DATE
        );
    }

    /**
     * @throws Exception
     */
    private function createTransferInitiatorDetailsWithArticle(): TransferInitiatorDetails
    {
        $data = $this->createTransferInitiatorDetails();
        $data->setRemittanceIdentifier(self::TEST_REMITTANCE_ID);
        $data->addArticle(new WebshopArticle(self::TEST_ARTICLE_NAME, self::TEST_ARTICLE_COUNT, self::TEST_AMOUNT));
        return $data;
    }

    private function calculateFingerprint(string $remittanceIdentifier): string
    {
        return Fingerprint::calculateExpectedMD5Fingerprint(
            self::TEST_SECRET,
            self::TEST_DATE,
            self::TEST_REFERENCE,
            self::TEST_ACCOUNT,
            $remittanceIdentifier,
            self::TEST_AMOUNT,
            self::TEST_CURRENCY,
            self::TEST_USER_ID
        );
    }
}