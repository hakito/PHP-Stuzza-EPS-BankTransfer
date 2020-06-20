<?php

namespace at\externet\eps_bank_transfer;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'MockTransport.php';

class SoCommunicatorTest extends BaseTest
{

    /** @var SoCommunicator */
    private $target;

    /** @var MockTransport */
    private $mTransport;

    /** @var RequestMock */
    private $httpResponseDummy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mTransport = new MockTransport();
        $this->mTransport->body = 'bar';
        $this->target = new SoCommunicator();
        $this->target->Transport = $this->mTransport;
        date_default_timezone_set('UTC');
    }

    public function testGetBanksNoExceptionWhenNoValidation()
    {
        $banks = $this->target->GetBanks(false);
        $this->assertEquals('bar', $banks);
    }

    public function testGetBanksCallsCorrectUrl()
    {
        $this->target->GetBanks(false);
        $this->assertEquals('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_6', $this->mTransport->lastUrl);
    }

    public function testGetBanksArray()
    {
        $this->mTransport->body = $this->GetEpsData('BankListSample.xml');

        $actual = $this->target->GetBanksArray();
        $expected = array(
            'Testbank' => array(
                'bic'   => 'TESTBANKXXX',
                'bezeichnung' => 'Testbank',
                'land'  => 'AT',
                'epsUrl' => 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6/23ea3d14-278c-4e81-a021-d7b77492b611'
            )
        );

        $this->assertEquals($expected, $actual);
    }

    public function testGetBanklistReadError()
    {
        $this->expectException(HttpResponseException::class, 'Could not load document. Server returned code: 404');
        $this->mTransport->code = 404;
        $this->target->GetBanks();
    }

    public function testTryGetBanksArrayReturnsNull()
    {
        $banks = $this->target->TryGetBanksArray();
        $this->assertEquals($banks, null);
    }

    public function testTryGetBanksArrayReturnsBanks()
    {
        $this->mTransport->body = $this->GetEpsData('BankListSample.xml');

        $actual = $this->target->TryGetBanksArray();
        $expected = array(
            'Testbank' => array(
                'bic'   => 'TESTBANKXXX',
                'bezeichnung' => 'Testbank',
                'land'  => 'AT',
                'epsUrl' => 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6/23ea3d14-278c-4e81-a021-d7b77492b611'
            )
        );

        $this->assertEquals($expected, $actual);
    }

    public function testSendTransferInitiatorDetailsThrowsValidationException()
    {
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->mTransport->body = 'invalidData';

        $this->expectException(XmlValidationException::class);
        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);
    }

    public function testSendTransferInitiatorDetailsToCorrectUrl()
    {
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->mTransport->body = $this->GetEpsData('BankResponseDetails004.xml');

        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);

        $this->assertEquals('https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6', $this->mTransport->lastUrl);
    }

    public function testSendTransferInitiatorDetailsToTestUrl()
    {
        $this->target = new SoCommunicator(true);
        $this->target->Transport = $this->mTransport;
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->mTransport->body = $this->GetEpsData('BankResponseDetails004.xml');

        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);

        $this->assertEquals('https://routing.eps.or.at/appl/epsSO-test/transinit/eps/v2_6', $this->mTransport->lastUrl);
    }

    public function testOverrideDefaultBaseUrl()
    {
        $this->target->BaseUrl = 'http://example.com';

        $this->mTransport->body = $this->GetEpsData('BankListSample.xml');
        $this->target->GetBanksArray();
        $this->assertEquals('http://example.com/data/haendler/v2_6', $this->mTransport->lastUrl);

        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->mTransport->body = $this->GetEpsData('BankResponseDetails004.xml');

        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);
        $this->assertEquals('http://example.com/transinit/eps/v2_6', $this->mTransport->lastUrl);

    }

    public function testSendTransferInitiatorDetailsThrowsExceptionOn404()
    {
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->mTransport->code = 404;
        $this->expectException(HttpResponseException::class, "Could not load document. Server returned code: 404");

        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);
    }

    public function testSendTransferInitiatorDetailsWithPreselectedBank()
    {
        $url = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6/23ea3d14-278c-4e81-a021-d7b77492b611';
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->mTransport->body = $this->GetEpsData('BankResponseDetails000.xml');

        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails, $url);

        $this->assertEquals($url, $this->mTransport->lastUrl);
    }

    public function testSendTransferInitiatorDetailsWithSecurityThrowsExceptionOnEmptySalt()
    {
        $url = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6/23ea3d14-278c-4e81-a021-d7b77492b611';
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->mTransport->body = $this->GetEpsData('BankResponseDetails000.xml');
        $this->target->ObscuritySuffixLength = 8;
        $this->expectException(\UnexpectedValueException::class, 'No security seed set when using security suffix.');

        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails, $url);
    }

    public function testSendTransferInitiatorDetailsWithSecurityAppendsHash()
    {
        $url = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_6/23ea3d14-278c-4e81-a021-d7b77492b611';
        $t = new TransferMsgDetails('a', 'b', 'c');
        $transferInitiatorDetails = new TransferInitiatorDetails('a', 'b', 'c', 'd', 'e', 'f', 0, $t);
        $transferInitiatorDetails->RemittanceIdentifier = 'Order1';
        $this->mTransport->body = $this->GetEpsData('BankResponseDetails000.xml');
        $this->target->ObscuritySuffixLength = 8;
        $this->target->ObscuritySeed = 'Some seed';

        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails, $url);
        $this->assertEquals('string', gettype($this->mTransport->lastPostBody));
        $this->assertStringContainsString('>'. 'Order1U294bWR3' . '<', $this->mTransport->lastPostBody);
    }

    public function testHandleConfirmationUrlThrowsExceptionOnMissingCallback()
    {
        $this->expectException(InvalidCallbackException::class);
        $this->target->HandleConfirmationUrl(null, null, null, 'php://temp');
    }

    public function testHandleConfirmationUrlReturnsErrorOnMissingCallback()
    {
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $message = null;
        try
        {
            $this->target->HandleConfirmationUrl(null, null, null, $temp);
        } catch (\at\externet\eps_bank_transfer\InvalidCallbackException $e)
        {
            $message = $e->getMessage();
        }
        $actual = file_get_contents($temp);
        XmlValidator::ValidateEpsProtocol($actual);
        $this->assertNotEmpty($message);
        $this->assertStringContainsString($message, $actual);
    }

    public function testHandleConfirmationUrlThrowsExceptionOnInvalidXml()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsInvalid.xml');
        $this->expectException(XmlValidationException::class);
        $this->target->HandleConfirmationUrl(function(){}, null, $dataPath, 'php://temp');
    }

    public function testHandleConfirmationUrlCallsCallback()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithoutSignature.xml');
        $actual = 'Nothing';
        $this->target->HandleConfirmationUrl(function($data) use (&$actual) {
            $actual = $data;
            return true;
            }, null, $dataPath, 'php://temp');
        $expected = file_get_contents($dataPath);
        $this->assertEquals($actual, $expected);
    }

    public function testHandleConfirmationUrlCallsCallbackWithBankConfirmationDetails()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithoutSignature.xml');
        $bankConfirmationDetails = null;
        $this->target->HandleConfirmationUrl(function($data, $bc) use (&$bankConfirmationDetails) {
            $bankConfirmationDetails = $bc;
            return true;
            }, null, $dataPath, 'php://temp');

        $this->assertEquals('AT1234567890XYZ', $bankConfirmationDetails->GetRemittanceIdentifier());
        $this->assertEquals('OK', $bankConfirmationDetails->GetStatusCode());
    }

    public function testHandleConfirmationUrlThrowsExceptionWhenCallbackDoesNotReturnTrue()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithoutSignature.xml');
        $this->expectException(CallbackResponseException::class);
        $this->target->HandleConfirmationUrl(function($data) {}, null, $dataPath, 'php://temp');
    }

    public function testHandleConfirmationUrlReturnsErrorWhenCallbackDoesNotReturnTrue()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithoutSignature.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $message = null;
        try
        {
            $this->target->HandleConfirmationUrl(function($data) {}, null, $dataPath, $temp);
        } catch (\at\externet\eps_bank_transfer\CallbackResponseException $e)
        {
            $message = $e->getMessage();
        }

        $actual = file_get_contents($temp);
        XmlValidator::ValidateEpsProtocol($actual);

        $this->assertNotEmpty($message);
        $this->assertStringContainsString('ShopResponseDetails>', $actual);
        $this->assertStringContainsString('ErrorMsg>', $actual);
        $this->assertStringContainsString($message, $actual);
    }

    public function testHandleConfirmationUrlVitalityCheckDoesNotCallBankConfirmationCallback()
    {
        $dataPath = $this->GetEpsDataPath('VitalityCheckDetails.xml');
        $actual = true;
        $this->target->HandleConfirmationUrl(function($data) use (&$actual)
                {
                    $actual = false;
                    return true;
                }, null, $dataPath, 'php://temp');
        $this->assertTrue($actual);
    }

    public function testHandleConfirmationUrlVitalityThrowsExceptionOnInvalidValidationCallback()
    {
        $dataPath = $this->GetEpsDataPath('VitalityCheckDetails.xml');
        $this->expectException(InvalidCallbackException::class);
        $this->target->HandleConfirmationUrl(function($data) {}, "invalid", $dataPath, 'php://temp');
    }

    public function testHandleConfirmationUrlVitalityReturnsErrorOnInvalidValidationCallback()
    {
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $message = null;
        try
        {
            $this->target->HandleConfirmationUrl(function($data) {}, "invalid", null, $temp);
        } catch (\at\externet\eps_bank_transfer\InvalidCallbackException $e)
        {
            $message = $e->getMessage();
        }
        $actual = file_get_contents($temp);
        XmlValidator::ValidateEpsProtocol($actual);
        $this->assertNotEmpty($message);
        $this->assertStringContainsString($message, $actual);
    }

    public function testHandleConfirmationUrlVitalityThrowsExceptionWhenCallbackDoesNotReturnTrue()
    {
        $dataPath = $this->GetEpsDataPath('VitalityCheckDetails.xml');
        $this->expectException(CallbackResponseException::class);
        $this->target->HandleConfirmationUrl(function() {}, function($data) {}, $dataPath, 'php://temp');
    }

    public function testHandleConfirmationUrlVitalityWritesInputToOutputstream()
    {
        $dataPath = $this->GetEpsDataPath('VitalityCheckDetails.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->target->HandleConfirmationUrl(function() {}, null, $dataPath, $temp);
        $expected = file_get_contents($dataPath);
        $actual = file_get_contents($temp);
        $this->assertEquals($expected, $actual);
    }

    public function testHandleConfirmationUrlReturnsErrorOnInvalidXml()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsInvalid.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        try
        {
            $this->target->HandleConfirmationUrl(function() {}, null, $dataPath, $temp);
        } catch (\at\externet\eps_bank_transfer\XmlValidationException $e)
        {
            // expected
        }
        $actual = file_get_contents($temp);
        XmlValidator::ValidateEpsProtocol($actual);
        $this->assertStringContainsString('ShopResponseDetails>', $actual);
        $this->assertStringContainsString('ErrorMsg>Error occured during XML validation</', $actual);
    }

    public function testHandleConfirmationUrlReturnsShopResponse()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithoutSignature.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->target->HandleConfirmationUrl(function() { return true; }, null, $dataPath, $temp);

        $actual = file_get_contents($temp);
        $this->assertStringContainsString(':ShopResponseDetails', $actual);
        $this->assertStringContainsString('SessionId>13212452dea<', $actual);
        $this->assertStringContainsString('StatusCode>OK<', $actual);
        $this->assertStringContainsString('PaymentReferenceIdentifier>120000302122320812201106461<', $actual);
    }

    public function testHandleConfirmationUrlReturnsShopResponseOnConfirmationWithSignature()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithSignature.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->target->HandleConfirmationUrl(function() { return true; }, null, $dataPath, $temp);

        $actual = file_get_contents($temp);
        $this->assertStringContainsString(':ShopResponseDetails', $actual);
        $this->assertStringContainsString('SessionId>String<', $actual);
        $this->assertStringContainsString('StatusCode>OK<', $actual);
        $this->assertStringContainsString('PaymentReferenceIdentifier>RIAT1234567890XYZ<', $actual);
    }

    public function testHandleConfirmationUrlReturnsErrorResponseOnCallbackException()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithSignature.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $catchedMessage = null;
        try
        {
            $this->target->HandleConfirmationUrl(function() { throw new \Exception('Something failed'); }, null, $dataPath, $temp);
        }
        catch (\Exception $e)
        {
            $catchedMessage = $e->getMessage();
        }
        $this->assertEquals('Something failed', $catchedMessage);

        $actual = file_get_contents($temp);
        XmlValidator::ValidateEpsProtocol($actual);
        $this->assertStringContainsString('ShopResponseDetails>', $actual);
        $this->assertStringNotContainsString('Something failed', $actual);
        $this->assertStringContainsString('ErrorMsg>An exception of type', $actual);
    }

    public function testHandleConfirmationUrlThrowsExceptionOnInvalidSecuritySuffix()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithSignature.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->target->ObscuritySuffixLength = 3;
        $this->target->ObscuritySeed = 'Foo';
        try
        {
            $this->target->HandleConfirmationUrl(function() { }, null, $dataPath, $temp);
        }
        catch (UnknownRemittanceIdentifierException $e)
        {
            // expected
        }

        $actual = file_get_contents($temp);
        XmlValidator::ValidateEpsProtocol($actual);
        $this->assertStringContainsString('ShopResponseDetails>', $actual);
        $this->assertStringContainsString('ErrorMsg>Unknown RemittanceIdentifier supplied', $actual);
    }

    public function testHandleConfirmationUrlThrowsExceptionOnInvalidSecuritySetup()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithSignature.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->target->ObscuritySuffixLength = 3;
        try
        {
            $this->target->HandleConfirmationUrl(function() { }, null, $dataPath, $temp);
        }
        catch (\UnexpectedValueException $e)
        {
            // expected
        }

        $actual = file_get_contents($temp);
        XmlValidator::ValidateEpsProtocol($actual);
        $this->assertStringContainsString('ShopResponseDetails>', $actual);
        $this->assertStringContainsString('ErrorMsg>An exception of type "UnexpectedValueException" occurred during handling of the confirmation url', $actual);
    }

    public function testHandleConfirmationUrlStripsSecurityHashFromRemittanceIdentifier()
    {
        $original = $this->GetEpsData('BankConfirmationDetailsWithoutSignature.xml');
        $expected = 'AT1234567890XYZ';
        $data = str_replace($expected, $expected . 'Rm8', $original);
        $dataPath = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        file_put_contents($dataPath, $data);

        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->target->ObscuritySuffixLength = 3;
        $this->target->ObscuritySeed = 'Foo';
        $bankConfirmationDetails = null;
        $this->target->HandleConfirmationUrl(function($raw, $bc) use (&$bankConfirmationDetails)
        {
            $bankConfirmationDetails = $bc;
            return true;
        }, null, $dataPath, $temp);

        $this->assertSame($expected, $bankConfirmationDetails->GetRemittanceIdentifier());
    }

    public function testWriteLog()
    {
        $dataPath = $this->GetEpsDataPath('VitalityCheckDetails.xml');
        $message = null;
        $this->target->LogCallback = function($m) use (&$message){ $message = $m; };
        $this->target->HandleConfirmationUrl(function() {}, function($data) {return true;}, $dataPath, 'php://temp');
        $this->assertEquals('Vitality Check', $message);
    }

    public function testWriteLogSendTransferInitiatorDetailsSuccess()
    {
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->mTransport->body = $this->GetEpsData('BankResponseDetails000.xml');
        $message = null;
        $this->target->LogCallback = function($m) use (&$message){ $message = $m; };

        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);

        $this->assertEquals('SUCCESS: Send payment order', $message);
    }

    public function testWriteLogSendTransferInitiatorDetailsFailed()
    {
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->mTransport->code = 400;
        $message = null;
        $this->target->LogCallback = function($m) use (&$message){ $message = $m; };

        try
        {
            $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);
        }
        catch (HttpResponseException $e)
        {}

        $this->assertEquals('FAILED: Send payment order', $message);
    }

    // HELPER FUNCTIONS

    /**
     *
     * @return TransferInitiatorDetails
     */
    private function getMockedTransferInitiatorDetails()
    {
        $simpleXml = $this->getMockBuilder(EpsXmlElement::class)
            ->setConstructorArgs(array('<xml/>'))
            ->getMock();
        $simpleXml->expects($this->any())
                ->method('asXML')
                ->will($this->returnValue('<xml>Mocked Data'));

        $transferInitiatorDetails = $this->getMockBuilder(TransferInitiatorDetails::class)
            ->disableOriginalConstructor()
            ->getMock();
        $transferInitiatorDetails->expects($this->any())
                ->method('GetSimpleXml')
                ->will($this->returnValue($simpleXml));

        $transferInitiatorDetails->RemittanceIdentifier = 'orderid';

        return $transferInitiatorDetails;
    }
}