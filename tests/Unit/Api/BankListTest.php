<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Api;

use Psa\EpsBankTransfer\Api\SoCommunicator;
use Psa\EpsBankTransfer\Domain\Bank;
use Psa\EpsBankTransfer\Domain\BankList;
use Psa\EpsBankTransfer\Exceptions\BankListException;
use Psa\EpsBankTransfer\Exceptions\XmlValidationException;
use Psa\EpsBankTransfer\Tests\Helper\SoCommunicatorTestTrait;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class BankListTest extends TestCase
{
    use SoCommunicatorTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpCommunicator();
    }

    /* ============================================================
     * Data Providers
     * ============================================================
     */
    public static function provideSoUrls(): array
    {
        return [
            'live' => [SoCommunicator::LIVE_MODE_URL, 'https://routing.eps.or.at/appl/epsSO/data/haendler/v2_6'],
            'test' => [SoCommunicator::TEST_MODE_URL, 'https://routing-test.eps.or.at/appl/epsSO/data/haendler/v2_6'],
        ];
    }

    /* ============================================================
     * Version dependent tests
     * ============================================================
     */

    public function testGetBanksV26(): void
    {
        $this->mockResponse(200, $this->loadFixture('BankListSample.xml'));

        $banks = $this->target->getBanks('2.6');

        $this->assertInstanceOf(BankList::class, $banks);
        $this->assertEquals([
            new Bank(
                'TESTBANKXXX',
                'Testbank',
                'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6/23ea3d14-278c-4e81-a021-d7b77492b611',
                'AT'
            ),
        ], $banks->getBanks());

        $lastUrl = $this->http->getLastRequestInfo()['url'];
        $this->assertStringContainsString('/v2_6', $lastUrl);
    }

    public function testGetBanksV27Throws(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Not implemented yet - waiting for XSD 2.7');

        $this->target->getBanks('2.7');
    }

    /**
     * @dataProvider provideSoUrls
     */
    public function testGetBanksWithDifferentSoUrls(string $soUrl, string $expectedUrl): void
    {
        $this->setUpCommunicator($soUrl);
        $this->mockResponse(200, $this->loadFixture('BankListSample.xml'));

        $banks = $this->target->getBanks('2.6');

        $this->assertInstanceOf(BankList::class, $banks);
        $this->assertEquals([
            new Bank(
                'TESTBANKXXX',
                'Testbank',
                'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6/23ea3d14-278c-4e81-a021-d7b77492b611',
                'AT'
            ),
        ], $banks->getBanks());
        $this->assertEquals($expectedUrl, $this->http->getLastRequestInfo()['url']);
    }

    /* ============================================================
     * Error handling
     * ============================================================
     */

    public function testGetBankListReadError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('GET https://routing.eps.or.at/appl/epsSO/data/haendler/v2_6 failed with HTTP 404');

        $this->mockResponse(404, 'Not found', ['Content-Type' => 'text/plain']);
        $this->target->getBanks('2.6');
    }

    public function testGetBanksThrowsValidationExceptionOnInvalidXml(): void
    {
        $this->mockResponse(200, 'invalidData');

        $this->expectException(XmlValidationException::class);
        $this->target->getBanks('2.6');
    }

    public function testGetBanksThrowsOnUnsupportedVersion(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported version');

        $this->target->getBanks('foo');
    }

    public function testGetBanksThrowsOnErrorResponse(): void
    {
        $this->mockResponse(200, $this->loadFixture('BankListError.xml'));

        $this->expectException(BankListException::class);
        $this->expectExceptionMessage('Error: Bank list retrieval failed');

        $this->target->getBanks('2.6');
    }

    /* ============================================================
     * Special cases
     * ============================================================
     */

    public function testOverrideDefaultBaseUrl(): void
    {
        $this->target->setBaseUrl('http://example.com');
        $this->mockResponse(200, $this->loadFixture('BankListSample.xml'));

        $this->target->getBanks('2.6');

        $this->assertEquals(
            'http://example.com/data/haendler/v2_6',
            $this->http->getLastRequestInfo()['url']
        );
    }

    public function testGetBanksHandlesEmptyList(): void
    {
        $this->mockResponse(200, $this->loadFixture('BankListEmpty.xml'));

        $banks = $this->target->getBanks('2.6');

        $this->assertInstanceOf(BankList::class, $banks);
        $this->assertEquals(new BankList([]), $banks);
    }
    
    
}
