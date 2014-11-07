<?php

namespace at\externet\eps_bank_transfer;

class SoCommunicatorTest extends BaseTest
{

    /** @var SoCommunicator */
    private $target;

    /** @var \org\cakephp\HttpSocketResponse */
    private $httpResponseDummy;

    public function setUp()
    {
        parent::setUp();
        $this->target = new SoCommunicator();
        $this->target->HttpSocket = $this->getMock('org\cakephp\HttpSocket');
        $this->httpResponseDummy = new \org\cakephp\HttpSocketResponse();
        $this->httpResponseDummy->code = 200;
        $this->httpResponseDummy->body = 'bar';
    }

    public function testGetBanksNoExceptionWhenNoValidation()
    {
        $this->target->HttpSocket->expects($this->once())
                ->method('get')
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_5')
                ->will($this->returnValue($this->httpResponseDummy));
        $banks = $this->target->GetBanks(false);
        $this->assertEquals('bar', $this->httpResponseDummy->body);
    }

    public function testGetBanksArray()
    {
        $this->httpResponseDummy->body = $this->GetEpsData('BankListSample.xml');
        $this->target->HttpSocket->expects($this->once())
                ->method('get')
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_5')
                ->will($this->returnValue($this->httpResponseDummy));
        
        $actual = $this->target->GetBanksArray();
        $expected = array(
            'Testbank' => array(
                'bic'   => 'TESTBANKXXX',
                'bezeichnung' => 'Testbank',
                'land'  => 'AT',
                'epsUrl' => 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_5/23ea3d14-278c-4e81-a021-d7b77492b611'
            )
        );
        $this->assertEquals($expected, $actual);
    }

    public function testGetBanklistReadError()
    {
        $this->setExpectedException('at\externet\eps_bank_transfer\HttpResponseException', 'Could not load document. Server returned code: 404');
        $this->httpResponseDummy->code = 404;
        $this->target->HttpSocket->expects($this->once())
                ->method('get')
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_5')
                ->will($this->returnValue($this->httpResponseDummy));
        $this->target->GetBanks();
    }


    public function testTryGetBanksArrayReturnsBanks()
    {
        $this->target->HttpSocket->expects($this->once())
                ->method('get')
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_5')
                ->will($this->returnValue($this->httpResponseDummy));

        $banks = $this->target->TryGetBanksArray();
        $this->assertEquals($banks, null);
    }

    public function testTryGetBanksArrayReturnsNull()
    {
        $this->httpResponseDummy->body = $this->GetEpsData('BankListSample.xml');
        $this->target->HttpSocket->expects($this->once())
                ->method('get')
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_5')
                ->will($this->returnValue($this->httpResponseDummy));

        $actual = $this->target->TryGetBanksArray();
        $expected = array(
            'Testbank' => array(
                'bic'   => 'TESTBANKXXX',
                'bezeichnung' => 'Testbank',
                'land'  => 'AT',
                'epsUrl' => 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_5/23ea3d14-278c-4e81-a021-d7b77492b611'
            )
        );
        $this->assertEquals($expected, $actual);
    }

    public function testSendTransferInitiatorDetailsThrowsValidationException()
    {
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->httpResponseDummy->body = 'invalidData';
        $this->target->HttpSocket->expects($this->once())
                ->method('post')
                ->with('https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_5')
                ->will($this->returnValue($this->httpResponseDummy));

        $this->setExpectedException('at\externet\eps_bank_transfer\XmlValidationException');
        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);
    }

    public function testSendTransferInitiatorDetailsCallsHttpPost()
    {
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->httpResponseDummy->body = $this->GetEpsData('BankResponseDetails004.xml');
        $this->target->HttpSocket->expects($this->once())
                ->method('post')
                ->with('https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_5')
                ->will($this->returnValue($this->httpResponseDummy));
        $actual = $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);
        $this->assertEquals($actual, $this->httpResponseDummy->body);
    }

    public function testSendTransferInitiatorDetailsThrowsExceptionOn404()
    {
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->httpResponseDummy->code = 404;
        $this->target->HttpSocket->expects($this->once())
                ->method('post')
                ->with('https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_5')
                ->will($this->returnValue($this->httpResponseDummy));
        $this->setExpectedException('at\externet\eps_bank_transfer\HttpResponseException', "Could not load document. Server returned code: 404");
        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);
    }

    public function testSendTransferInitiatorDetailsWithPreselectedBank()
    {
        $url = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_5/23ea3d14-278c-4e81-a021-d7b77492b611';
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->httpResponseDummy->body = $this->GetEpsData('BankResponseDetails000.xml');
        $this->target->HttpSocket->expects($this->once())
                ->method('post')
                ->with($url)
                ->will($this->returnValue($this->httpResponseDummy));
        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails, $url);
    }
    
    public function testSendTransferInitiatorDetailsWithSecurityThrowsExceptionOnEmptySalt()
    {
        $url = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_5/23ea3d14-278c-4e81-a021-d7b77492b611';
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->httpResponseDummy->body = $this->GetEpsData('BankResponseDetails000.xml');
        $this->target->ObscuritySuffixLength = 8;

        $this->setExpectedException('UnexpectedValueException', 'No security seed set when using security suffix.');
        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails, $url);
    }
    
    public function testSendTransferInitiatorDetailsWithSecurityAppendsHash()
    {
        $url = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_5/23ea3d14-278c-4e81-a021-d7b77492b611';
        $t = new TransferMsgDetails('a', 'b', 'c');
        $transferInitiatorDetails = new TransferInitiatorDetails('a', 'b', 'c', 'd', 'e', 'f', 0, $t);
        $transferInitiatorDetails->RemittanceIdentifier = 'Order1';
        $this->httpResponseDummy->body = $this->GetEpsData('BankResponseDetails000.xml');
        $this->target->ObscuritySuffixLength = 8;
        $this->target->ObscuritySeed = 'Some seed';
        
        $this->target->HttpSocket->expects($this->once())
                ->method('post')
                ->with($url, $this->stringContains('>'. $transferInitiatorDetails->RemittanceIdentifier . 'cca2ef99' . '<'))
                ->will($this->returnValue($this->httpResponseDummy));
        
        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails, $url);
    }
    
    public function testHandleConfirmationUrlThrowsExceptionOnMissingCallback()
    {
        $this->setExpectedException('at\externet\eps_bank_transfer\InvalidCallbackException');
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
        $this->assertContains($message, $actual);        
    }

    public function testHandleConfirmationUrlThrowsExceptionOnInvalidXml()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsInvalid.xml');
        $this->setExpectedException('at\externet\eps_bank_transfer\XmlValidationException');
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
        $this->setExpectedException('at\externet\eps_bank_transfer\CallbackResponseException');
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
        $this->assertContains('ShopResponseDetails>', $actual);
        $this->assertContains('ErrorMsg>', $actual);        
        $this->assertContains($message, $actual);
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
        $this->setExpectedException('at\externet\eps_bank_transfer\InvalidCallbackException');
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
        $this->assertContains($message, $actual);        
    }    
    
    public function testHandleConfirmationUrlVitalityThrowsExceptionWhenCallbackDoesNotReturnTrue()
    {
        $dataPath = $this->GetEpsDataPath('VitalityCheckDetails.xml');        
        $this->setExpectedException('at\externet\eps_bank_transfer\CallbackResponseException');
        $this->target->HandleConfirmationUrl(function() {}, function($data) {}, $dataPath, 'php://temp'); 
    }

    public function testHandleConfirmationUrlVitalityWithoutCallback()
    {
        $dataPath = $this->GetEpsDataPath('VitalityCheckDetails.xml');
        $this->target->HandleConfirmationUrl(function() {}, null, $dataPath, 'php://temp');
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
        $this->assertContains('ShopResponseDetails>', $actual);
        $this->assertContains('ErrorMsg>Error occured during XML validation</', $actual);
    }
    
    public function testHandleConfirmationUrlReturnsShopResponse()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithoutSignature.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->target->HandleConfirmationUrl(function() { return true; }, null, $dataPath, $temp);
        
        $actual = file_get_contents($temp);        
        $this->assertContains(':ShopResponseDetails', $actual);        
        $this->assertContains('SessionId>13212452dea<', $actual);   
        $this->assertContains('StatusCode>OK<', $actual);
        $this->assertContains('PaymentReferenceIdentifier>120000302122320812201106461<', $actual);
    }
    
    public function testHandleConfirmationUrlReturnsShopResponseOnConfirmationWithSignature()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithSignature.xml');
        $temp = tempnam(sys_get_temp_dir(), 'SoCommunicatorTest_');
        $this->target->HandleConfirmationUrl(function() { return true; }, null, $dataPath, $temp);
        
        $actual = file_get_contents($temp);        
        $this->assertContains(':ShopResponseDetails', $actual);        
        $this->assertContains('SessionId>String<', $actual);   
        $this->assertContains('StatusCode>OK<', $actual);
        $this->assertContains('PaymentReferenceIdentifier>RIAT1234567890XYZ<', $actual);
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
        $this->assertContains('ShopResponseDetails>', $actual);
        $this->assertNotContains('Something failed', $actual);
        $this->assertContains('ErrorMsg>An exception of type', $actual);
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
        $this->assertContains('ShopResponseDetails>', $actual);        
        $this->assertContains('ErrorMsg>Unknown RemittanceIdentifier supplied', $actual);
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
        $this->assertContains('ShopResponseDetails>', $actual);        
        $this->assertContains('ErrorMsg>An exception of type "UnexpectedValueException" occurred during handling of the confirmation url', $actual);
    }
    
    public function testHandleConfirmationUrlStripsSecurityHashFromRemittanceIdentifier()            
    {
        $original = $this->GetEpsData('BankConfirmationDetailsWithoutSignature.xml');
        $expected = 'AT1234567890XYZ';
        $data = str_replace($expected, $expected . 'd33', $original);
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
    
    // HELPER FUNCTIONS

    /**
     * 
     * @return TransferInitiatorDetails
     */
    private function getMockedTransferInitiatorDetails()
    {
        $simpleXml = $this->getMock('at\externet\eps_bank_transfer\EpsXmlElement', null, array('<xml/>'));
        $simpleXml->expects($this->any())
                ->method('asXML')
                ->will($this->returnValue('<xml>Mocked Data'));

        $transferInitiatorDetails = $this->getMockBuilder('at\externet\eps_bank_transfer\TransferInitiatorDetails')->disableOriginalConstructor()->getMock();
        $transferInitiatorDetails->expects($this->any())
                ->method('GetSimpleXml')
                ->will($this->returnValue($simpleXml));

        $transferInitiatorDetails->RemittanceIdentifier = 'orderid';

        return $transferInitiatorDetails;
    }
}