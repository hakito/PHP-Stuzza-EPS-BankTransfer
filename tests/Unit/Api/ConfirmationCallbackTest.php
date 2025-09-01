<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Api;

use DateTime;
use Psa\EpsBankTransfer\Domain\BankConfirmationDetails;
use Psa\EpsBankTransfer\Domain\VitalityCheckDetails;
use Psa\EpsBankTransfer\Exceptions\CallbackResponseException;
use Psa\EpsBankTransfer\Exceptions\EpsException;
use Psa\EpsBankTransfer\Exceptions\InvalidCallbackException;
use Psa\EpsBankTransfer\Exceptions\XmlValidationException;
use Psa\EpsBankTransfer\Tests\Helper\SoCommunicatorTestTrait;
use Psa\EpsBankTransfer\Utilities\XmlValidator;
use PHPUnit\Framework\TestCase;

class ConfirmationCallbackTest extends TestCase
{
    use SoCommunicatorTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpCommunicator();
    }

    private function handleConfirmation(
        ?callable $confirmationCallback,
        ?callable $vitalityCheckCallback,
        string $xmlFile,
        ?string $outputFile = null
    ): void {
        $dataPath = $this->fixturePath($xmlFile);
        $this->target->handleConfirmationUrl(
            $confirmationCallback,
            $vitalityCheckCallback,
            $dataPath,
            $outputFile ?? 'php://temp'
        );
    }

    public function testHandleConfirmationUrlThrowsExceptionOnMissingCallback(): void
    {
        $this->expectException(InvalidCallbackException::class);
        $this->expectExceptionMessage('ConfirmationCallback not callable or missing');
        $this->handleConfirmation(null, null, 'BankConfirmationDetailsWithoutSignature.xml');
    }

    public function testHandleConfirmationUrlReturnsErrorOnMissingCallback(): void
    {
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        try {
            $this->handleConfirmation(null, null, 'BankConfirmationDetailsWithoutSignature.xml', $temp);
        } catch (InvalidCallbackException $e) {
            $msg = $e->getMessage();
        } finally {
            $actual = file_get_contents($temp);
            XmlValidator::validateEpsProtocol($actual);
            $this->assertStringContainsString($msg, $actual);
            @unlink($temp);
        }
    }

    public function testHandleConfirmationUrlThrowsExceptionOnInvalidXml(): void
    {
        $this->expectException(XmlValidationException::class);
        $this->handleConfirmation(
            function () { return true; },
            null,
            'V26/BankConfirmationDetailsInvalid.xml'
        );
    }

    public function testHandleConfirmationUrlCallsCallback(): void
    {
        $actual = 'Nothing';
        $this->handleConfirmation(
            function ($data) use (&$actual) {
                $actual = $data;
                return true;
            },
            null,
            'V26/BankConfirmationDetailsWithoutSignature.xml'
        );

        $expected = file_get_contents($this->fixturePath('V26/BankConfirmationDetailsWithoutSignature.xml'));
        $this->assertSame($expected, $actual);
    }

    /**
     * @throws XmlValidationException
     */
    public function testHandleConfirmationUrlCallsCallbackWithBankConfirmationDetails(): void
    {
        $bankDetails = null;
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->handleConfirmation(
            function ($raw, $bc) use (&$bankDetails) {
                $bankDetails = $bc;
                return true;
            },
            null,
            'V26/BankConfirmationDetailsWithoutSignature.xml',
            $temp
        );

        $expectedBankDetails = new BankConfirmationDetails(
            '13212452dea',
            'AT1234567890XYZ',
            'AAAAAAAAAAA',
            new DateTime('2007-03-19T11:11:00.000000-0500'),
            '120000302122320812201106461',
            'OK'
        );
        $this->assertEquals($expectedBankDetails, $bankDetails);

        $actual = file_get_contents($temp);
        XmlValidator::validateEpsProtocol($actual);
        $this->assertXmlEqualsFixture('ShopResponseDetailsOK.xml', $actual);
    }

    public function testHandleConfirmationUrlThrowsExceptionWhenCallbackDoesNotReturnTrue(): void
    {
        $this->expectException(CallbackResponseException::class);
        $this->expectExceptionMessage('Confirmation callback must return true');
        $this->handleConfirmation(
            function () { return null; },
            null,
            'V26/BankConfirmationDetailsWithoutSignature.xml'
        );
    }

    /**
     * @throws XmlValidationException
     */
    public function testHandleConfirmationUrlReturnsErrorWhenCallbackDoesNotReturnTrue(): void
    {
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        try {
            $this->handleConfirmation(
                function () {
                    return null;
                },
                null,
                'V26/BankConfirmationDetailsWithoutSignature.xml',
                $temp
            );
        } catch (CallbackResponseException $e) {
            $msg = $e->getMessage();
        } finally {
            $actual = file_get_contents($temp);
            XmlValidator::validateEpsProtocol($actual);
            $this->assertStringContainsString($msg, $actual);
            $this->assertXmlEqualsFixture('ShopResponseDetailsError.xml', $actual);
            @unlink($temp);
        }
    }

    public function testHandleConfirmationUrlVitalityCheckDoesNotCallBankConfirmationCallback(): void
    {
        $called = false;
        $this->handleConfirmation(
            function () use (&$called) { $called = true; return true; },
            null,
            'V26/VitalityCheckDetails.xml'
        );
        $this->assertFalse($called);
    }

    public function testHandleConfirmationUrlVitalityThrowsExceptionWhenCallbackDoesNotReturnTrue(): void
    {
        $this->expectException(CallbackResponseException::class);
        $this->expectExceptionMessage('Vitality check callback must return true');
        $this->handleConfirmation(
            function () {
                return true;
            },
            function () {
                return false;
            },
            'V26/VitalityCheckDetails.xml'
        );
    }

    public function testHandleConfirmationUrlVitalityWritesInputToOutputStream(): void
    {
        $vitalityDetails = null;
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $rawXml = file_get_contents($this->fixturePath('V26/VitalityCheckDetails.xml'));
        $this->target->handleConfirmationUrl(
            function () {
                return true;
            },
            function ($xml, $vc) use ($rawXml, &$vitalityDetails) {
                $this->assertSame($rawXml, $xml);
                $vitalityDetails = $vc;
                return true;
            },
            $this->fixturePath('V26/VitalityCheckDetails.xml'),
            $temp
        );

        $expectedVitalityDetails = new VitalityCheckDetails(
            'AT1234567890XYZ'
        );
        $this->assertEquals($expectedVitalityDetails, $vitalityDetails);

        $actual = file_get_contents($temp);
        XmlValidator::validateEpsProtocol($actual);
        $this->assertXmlEqualsFixture('V26/VitalityCheckDetails.xml', file_get_contents($temp));
    }

    public function testHandleConfirmationUrlReturnsErrorOnInvalidXml(): void
    {
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->expectException(EpsException::class);
        $this->expectExceptionMessage('XML does not validate against XSD');
        try {
            $this->handleConfirmation(
                function () {
                    return true;
                },
                null,
                'V26/BankConfirmationDetailsInvalid.xml',
                $temp
            );
        } finally {
            $actual = file_get_contents($temp);
            XmlValidator::validateEpsProtocol($actual);
            $this->assertXmlEqualsFixture('ShopResponseDetailsXMLError.xml', $actual);
            @unlink($temp);
        }
    }
    public function testHandleConfirmationUrlThrowsLogicExceptionForV27(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Not implemented yet - waiting for XSD 2.7');
        // Use any XML fixture, the version selection happens via the argument below
        $this->target->handleConfirmationUrl(
            function () { return true; },
            null,
            $this->fixturePath('V26/BankConfirmationDetailsWithoutSignature.xml'),
            'php://temp',
            '2.7'
        );
    }
}