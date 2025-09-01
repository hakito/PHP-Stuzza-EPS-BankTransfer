<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Api;

use Psa\EpsBankTransfer\Api\SoCommunicator;
use Psa\EpsBankTransfer\Domain\ProtocolDetails;
use Psa\EpsBankTransfer\Exceptions\XmlValidationException;
use Psa\EpsBankTransfer\Requests\Parts\PaymentFlowUrls;
use Psa\EpsBankTransfer\Requests\TransferInitiatorDetails;
use Psa\EpsBankTransfer\Tests\Helper\SoCommunicatorTestTrait;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use UnexpectedValueException;

class TransferInitiatorDetailsTest extends TestCase
{
    use SoCommunicatorTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpCommunicator();
    }

    private function getMockedTransferInitiatorDetails(): TransferInitiatorDetails
    {
        $urls = new PaymentFlowUrls(
            'https://example.com/confirmation',
            'https://example.com/success',
            'https://example.com/failure'
        );

        $ti = new TransferInitiatorDetails(
            'TestShop', 'secret123', 'TESTBANKXXX',
            'Test Company GmbH', 'AT611904300234573201', 'REF123456789',
            12050, $urls
        );

        $ti->setRemittanceIdentifier('orderid');
        return $ti;
    }

    public static function provideTransferInitiatorUrls(): array
    {
        return [
            'live' => [
                SoCommunicator::LIVE_MODE_URL,
                'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6'
            ],
            'test' => [
                SoCommunicator::TEST_MODE_URL,
                'https://routing-test.eps.or.at/appl/epsSO/transinit/eps/v2_6'
            ],
        ];
    }

    /**
     * @dataProvider provideTransferInitiatorUrls
     */
    public function testSendTransferInitiatorDetailsToCorrectUrl(string $baseUrl, string $expectedUrl): void
    {
        $this->setUpCommunicator($baseUrl);
        $this->mockResponse(200, $this->loadFixture('V26/BankResponseDetails004.xml'));

        $response = $this->target->sendTransferInitiatorDetails(
            $this->getMockedTransferInitiatorDetails(),
            '2.6'
        );

        $expected = new ProtocolDetails(
            '004',
            'merchant not found!',
            null,
            null
        );

        $this->assertEquals($expected, $response);
        $this->assertEquals($expectedUrl, $this->http->getLastRequestInfo()['url']);
    }

    /* ============================================================
     * Version dependent tests
     * ============================================================
     */

    public function testSendTransferInitiatorDetailsV27Throws(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Not implemented yet - waiting for XSD 2.7');

        $this->target->sendTransferInitiatorDetails($this->getMockedTransferInitiatorDetails(), '2.7');
    }

    public function testSendTransferInitiatorDetailsThrowsOnUnsupportedVersion(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported version');

        $this->target->sendTransferInitiatorDetails($this->getMockedTransferInitiatorDetails(), 'foo');
    }

    /* ============================================================
     * Error handling
     * ============================================================
     */

    public function testSendTransferInitiatorDetailsThrowsValidationException(): void
    {
        $this->expectException(XmlValidationException::class);
        $this->mockResponse(200, 'invalidData');

        $this->target->sendTransferInitiatorDetails($this->getMockedTransferInitiatorDetails(), '2.6');
    }

    public function testSendTransferInitiatorDetailsThrowsExceptionOn404(): void
    {
        $this->expectException(RuntimeException::class);
        $this->mockResponse(404, 'Not found');

        $this->target->sendTransferInitiatorDetails($this->getMockedTransferInitiatorDetails(), '2.6');
    }

    public function testSendTransferInitiatorDetailsThrowsExceptionOnWrongContentType(): void
    {
        $this->mockResponse(200, '<xml>valid but wrong header</xml>', ['Content-Type' => 'text/plain']);

        $this->expectException(XmlValidationException::class);
        $this->target->sendTransferInitiatorDetails($this->getMockedTransferInitiatorDetails(), '2.6');
    }

    /* ============================================================
     * URL tests
     * ============================================================
     */


    public function testSendTransferInitiatorDetailsWithOverriddenBaseUrl(): void
    {
        $this->target->setBaseUrl('http://example.com');
        $this->mockResponse(200, $this->loadFixture('V26/BankResponseDetails004.xml'));

        $this->target->sendTransferInitiatorDetails($this->getMockedTransferInitiatorDetails(), '2.6');

        $this->assertEquals(
            'http://example.com/transinit/eps/v2_6',
            $this->http->getLastRequestInfo()['url']
        );
    }

    public function testSendTransferInitiatorDetailsWithPreselectedBank(): void
    {
        $url = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6/23ea3d14-278c-4e81-a021-d7b77492b611';
        $this->mockResponse(200, $this->loadFixture('V26/BankResponseDetails000.xml'));

        $response = $this->target->sendTransferInitiatorDetails($this->getMockedTransferInitiatorDetails(), '2.6', $url);

        $expected = new ProtocolDetails(
            '000',
            'Keine Fehler',
            'http://epsbank.at/asdk3935jdlf043',
            null
        );

        $this->assertEquals($expected, $response);
        $this->assertEquals($url, $this->http->getLastRequestInfo()['url']);
    }
    
    /* ============================================================
     * Security tests
     * ============================================================
     */

    public function testSendTransferInitiatorDetailsWithSecurityThrowsExceptionOnEmptySalt(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $details = $this->getMockedTransferInitiatorDetails();
        $details->setObscuritySuffixLength(8);

        $this->mockResponse(200, $this->loadFixture('V26/BankResponseDetails000.xml'));
        $this->target->sendTransferInitiatorDetails($details, '2.6');
    }

    public function testSendTransferInitiatorDetailsWithSecurityAppendsHash(): void
    {
        $this->mockResponse(200, $this->loadFixture('V26/BankResponseDetails000.xml'));

        $t = new PaymentFlowUrls('a', 'b', 'c');
        $transferInitiatorDetails = new TransferInitiatorDetails('a', 'b', 'c',
            'd', 'e', 'f', 0, $t, null, 8, 'Some seed');
        $transferInitiatorDetails->setRemittanceIdentifier('Order1');

        $url = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6/23ea3d14-278c-4e81-a021-d7b77492b611';
        $this->target->sendTransferInitiatorDetails($transferInitiatorDetails, '2.6', $url);

        // Only verify we appended the hash-related values into body; response mapping and URL are covered elsewhere
        $body = $this->http->getLastRequestInfo()['body'];
        // The remittance identifier must be suffixed with an 8-char sha256 hash fragment
        $this->assertMatchesRegularExpression('/Order1[a-f0-9]{8}/i', $body);
    }

    /* ============================================================
     * Request body tests
     * ============================================================
     */

    public function testSendTransferInitiatorDetailsRequestContainsMandatoryFields(): void
    {
        $this->mockResponse(200, $this->loadFixture('V26/BankResponseDetails004.xml'));

        // Trigger sending to build request body (response mapping is covered by dedicated tests)
        $this->target->sendTransferInitiatorDetails($this->getMockedTransferInitiatorDetails(), '2.6');

        $body = $this->http->getLastRequestInfo()['body'];
        $this->assertStringContainsString('orderid', $body); // remittanceIdentifier
        $this->assertStringContainsString('AT611904300234573201', $body); // IBAN
        $this->assertStringContainsString('120.5', $body); // amount
        $this->assertStringContainsString('TestShop', $body); // userId
        $this->assertStringContainsString('Test Company GmbH', $body); // beneficiaryName
        $this->assertStringContainsString('TESTBANKXXX', $body); // bankId
        $this->assertStringContainsString('REF123456789', $body); // referenceId
        $this->assertStringContainsString('https://example.com/confirmation', $body);
        $this->assertStringContainsString('https://example.com/success', $body);
        $this->assertStringContainsString('https://example.com/failure', $body);
    }


    /* ============================================================
     * Amount formatting tests
     * ============================================================
     */

    /**
     * @dataProvider provideValidAmounts
     */
    public function testSendTransferInitiatorDetailsFormatsAmountCorrectly($inputAmount, string $expectedInXml): void
    {
        $this->mockResponse(200, $this->loadFixture('V26/BankResponseDetails004.xml'));

        $t = new PaymentFlowUrls('https://example.com/confirmation', 'https://example.com/success', 'https://example.com/failure');

        $ti = new TransferInitiatorDetails(
            'TestShop',
            'secret123',
            'TESTBANKXXX',
            'Test Company GmbH',
            'AT611904300234573201',
            'REF123456789',
            $inputAmount,
            $t
        );
        $ti->setRemittanceIdentifier('orderid');

        $this->target->sendTransferInitiatorDetails($ti, '2.6');

        $body = $this->http->getLastRequestInfo()['body'];
        $this->assertStringContainsString($expectedInXml, $body);
    }

    public static function provideValidAmounts(): array
    {
        return [
            'zero' => [0, '0.0'],
            'one cent' => [1, '0.01'],
            'twelve cents' => [12, '0.12'],
            'one euro' => [100, '1.0'],
            'ten euros' => [1000, '10.0'],
            '123 euro 45 cent' => [12345, '123.45'],
            'string as int' => ['12345', '123.45'],
            'max cents' => [99, '0.99'],
            'max amount' => [999999999, '9999999.99'],
        ];
    }

    /**
     * @dataProvider provideInvalidAmounts
     */
    public function testSendTransferInitiatorDetailsThrowsOnInvalidAmount($invalidAmount): void
    {
        $t = new PaymentFlowUrls('a', 'b', 'c');

        $this->expectException(\InvalidArgumentException::class);

        new TransferInitiatorDetails(
            'TestShop',
            'secret123',
            'TESTBANKXXX',
            'Test Company GmbH',
            'AT611904300234573201',
            'REF123456789',
            $invalidAmount,
            $t
        );
    }

    public static function provideInvalidAmounts(): array
    {
        return [
            'float'         => [123.45],
            'string float'  => ['123.45'],
            'null'          => [null],
            'array'         => [[100]],
            'object'        => [(object)['val' => 100]],
        ];
    }
}
