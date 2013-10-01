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
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_4')
                ->will($this->returnValue($this->httpResponseDummy));
        $banks = $this->target->GetBanks(false);
        $this->assertEquals('bar', $this->httpResponseDummy->body);
    }

    public function testGetBanksArray()
    {
        $this->httpResponseDummy->body = $this->GetEpsData('BankListSample.xml');
        $this->target->HttpSocket->expects($this->once())
                ->method('get')
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_4')
                ->will($this->returnValue($this->httpResponseDummy));
        
        $actual = $this->target->GetBanksArray();
        $expected = array(
            'Testbank' => array(
                'bic'   => 'TESTBANKXXX',
                'bezeichnung' => 'Testbank',
                'land'  => 'AT',
                'epsUrl' => 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_4/23ea3d14-278c-4e81-a021-d7b77492b611'
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
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_4')
                ->will($this->returnValue($this->httpResponseDummy));
        $this->target->GetBanks();
    }


    public function testTryGetBanksArrayReturnsBanks()
    {
        $this->target->HttpSocket->expects($this->once())
                ->method('get')
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_4')
                ->will($this->returnValue($this->httpResponseDummy));

        $banks = $this->target->TryGetBanksArray();
        $this->assertEquals($banks, null);
    }

    public function testTryGetBanksArrayReturnsNull()
    {
        $this->httpResponseDummy->body = $this->GetEpsData('BankListSample.xml');
        $this->target->HttpSocket->expects($this->once())
                ->method('get')
                ->with('https://routing.eps.or.at/appl/epsSO/data/haendler/v2_4')
                ->will($this->returnValue($this->httpResponseDummy));

        $actual = $this->target->TryGetBanksArray();
        $expected = array(
            'Testbank' => array(
                'bic'   => 'TESTBANKXXX',
                'bezeichnung' => 'Testbank',
                'land'  => 'AT',
                'epsUrl' => 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_4/23ea3d14-278c-4e81-a021-d7b77492b611'
            )
        );
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateTransferIinitiatorDetails()
    {
        $webshopArticle = new WebshopArticle("Toaster", 1, 15000);
        $transferMsgDetails = new TransferMsgDetails("http://10.18.70.8:7001/vendorconfirmation", "http://10.18.70.8:7001/transactionok?danke.asp", "http://10.18.70.8:7001/transactionnok?fehler.asp");
        $transferMsgDetails->TargetWindowNok = $transferMsgDetails->TargetWindowOk = 'Mustershop';

        $data = new TransferInitiatorDetails('AKLJS231534', 'topSecret', 'GAWIATW1XXX', 'Max Mustermann', 'AT611904300234573201', '1234567890ABCDEFG', 'AT1234567890XYZ', 15000, $webshopArticle, $transferMsgDetails, '2007-03-16');
        $aSimpleXml = $data->GetSimpleXml();

        $eDom = new \DOMDocument();
        $eDom->loadXML($this->GetEpsData('TransferInitiatorDetailsWithoutSignature.xml'));
        $eDom->formatOutput = true;
        $eDom->preserveWhiteSpace = false;
        $eDom->normalizeDocument();

        $this->assertEquals($aSimpleXml->asXML(), $eDom->saveXML());
    }

    public function testSendTransferInitiatorDetailsThrowsValidationException()
    {
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->httpResponseDummy->body = 'invalidData';
        $this->target->HttpSocket->expects($this->once())
                ->method('post')
                ->with('https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_4')
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
                ->with('https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_4')
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
                ->with('https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_4')
                ->will($this->returnValue($this->httpResponseDummy));
        $this->setExpectedException('at\externet\eps_bank_transfer\HttpResponseException', "Could not load document. Server returned code: 404");
        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails);
    }

    public function testSendTransferInitiatorDetailsWithPreselectedBank()
    {
        $url = 'https://routing.eps.or.at/appl/epsSO/transinit/eps/v2_4/23ea3d14-278c-4e81-a021-d7b77492b611';
        $transferInitiatorDetails = $this->getMockedTransferInitiatorDetails();
        $this->httpResponseDummy->body = $this->GetEpsData('BankResponseDetails000.xml');
        $this->target->HttpSocket->expects($this->once())
                ->method('post')
                ->with($url)
                ->will($this->returnValue($this->httpResponseDummy));
        $this->target->SendTransferInitiatorDetails($transferInitiatorDetails, $url);
    }

    public function testHandleConfirmationUrlThrowsExceptionOnMissingCallback()
    {
        $this->setExpectedException('at\externet\eps_bank_transfer\InvalidCallbackException');
        $this->target->HandleConfirmationUrl(null);
    }

    public function testHandleConfirmationUrlThrowsExceptionOnInvalidXml()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsInvalid.xml');
        $this->setExpectedException('at\externet\eps_bank_transfer\XmlValidationException');
        $this->target->HandleConfirmationUrl(function(){}, $dataPath);
    }

    public function testHandleConfirmationUrlCallsCallback()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithoutSignature.xml');
        $actual = 'Nothing';
        $this->target->HandleConfirmationUrl(function($data) use (&$actual) {
            $actual = $data;
            return true;
            }, $dataPath);
        $expected = file_get_contents($dataPath);
        $this->assertEquals($actual, $expected);
    }

    public function testHandleConfirmationUrlThrowsExceptionWhenCallbackDoesNotReturnTrue()
    {
        $dataPath = $this->GetEpsDataPath('BankConfirmationDetailsWithoutSignature.xml');
        $this->setExpectedException('at\externet\eps_bank_transfer\CallbackResponseException');
        $this->target->HandleConfirmationUrl(function($data) {}, $dataPath);
    }

    
    // HELPER FUNCTIONS

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
        return $transferInitiatorDetails;
    }
}