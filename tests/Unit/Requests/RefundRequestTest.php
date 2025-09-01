<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Requests;

use DateTime;
use Exception;
use Psa\EpsBankTransfer\Internal\Generated\Refund\Amount;
use Psa\EpsBankTransfer\Internal\Generated\Refund\AuthenticationDetails;
use Psa\EpsBankTransfer\Internal\Generated\Refund\EpsRefundRequest;
use Psa\EpsBankTransfer\Serializer\SerializerFactory;
use Psa\EpsBankTransfer\Tests\Helper\XmlFixtureTestTrait;
use Psa\EpsBankTransfer\Utilities\Fingerprint;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

class RefundRequestTest extends TestCase
{
    
    use XmlFixtureTestTrait;

    /** @var SerializerInterface */
    private $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serializer = SerializerFactory::create();
    }

    /**
     * @dataProvider refundRequestDataProvider
     * @throws Exception
     */
    public function testGenerateRefundRequestWithReason(
        string  $CreDtTm,
        string  $TransactionId,
        string  $MerchantIBAN,
        float   $Amount,
        string  $AmountCurrencyIdentifier,
        string  $UserId,
        ?string $RefundReference,
        string  $expectedXmlFile
    )
    {
        $refundRequest = new EpsRefundRequest();
        $refundRequest->setCreDtTm(new DateTime($CreDtTm));
        $refundRequest->setTransactionId($TransactionId);
        $refundRequest->setMerchantIBAN($MerchantIBAN);

        $amount = new Amount($Amount);
        $amount->setAmountCurrencyIdentifier($AmountCurrencyIdentifier);
        $refundRequest->setAmount($amount);

        $authenticationDetails = new AuthenticationDetails();
        $authenticationDetails->setUserId($UserId);
        $authenticationDetails->setSHA256Fingerprint(
            Fingerprint::generateSHA256Fingerprint(
                "fluxkompensator!",
                $CreDtTm,
                $TransactionId,
                $MerchantIBAN,
                (string)$Amount,
                $AmountCurrencyIdentifier,
                $UserId,
                $RefundReference
            )
        );
        $refundRequest->setAuthenticationDetails($authenticationDetails);

        if ($RefundReference !== null) {
            $refundRequest->setRefundReference($RefundReference);
        }

        $xml = $this->serializer->serialize($refundRequest, 'xml');

        $this->assertXmlEqualsFixture($expectedXmlFile, $xml);
    }

    public function refundRequestDataProvider(): array
    {
        return [
            'with_refund_reference' => [
                "2024-09-25T08:09:53.454+02:00",
                "epsJMG15K752",
                "AT175700054011014943",
                10.00,
                "EUR",
                "HYPTAT22XXX_143921",
                "REFUND-123456789",
                "RefundRequest.xml"
            ],
            'without_refund_reference' => [
                "2025-09-25T08:09:53.454+02:00",
                "epsJMG15K753",
                "AT175700054011014943",
                12.90,
                "EUR",
                "HYPTAT22XXX_143921",
                null,
                "RefundRequestWithoutReason.xml"
            ],
        ];
    }
}