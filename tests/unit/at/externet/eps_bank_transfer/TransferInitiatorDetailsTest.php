<?php

namespace at\externet\eps_bank_transfer;

class TransferInitiatorDetailsTest extends BaseTest
{
    public function testGenerateTransferInitiatorDetails()
    {
        $webshopArticle = new WebshopArticle("Toaster", 1, 15000);
        $transferMsgDetails = new TransferMsgDetails("http://10.18.70.8:7001/vendorconfirmation", "http://10.18.70.8:7001/transactionok?danke.asp", "http://10.18.70.8:7001/transactionnok?fehler.asp");
        $transferMsgDetails->TargetWindowNok = $transferMsgDetails->TargetWindowOk = 'Mustershop';

        $data = new TransferInitiatorDetails('AKLJS231534', 'topSecret', 'GAWIATW1XXX', 'Max Mustermann', 'AT611904300234573201', '1234567890ABCDEFG', 15000, $transferMsgDetails, '2007-03-16');
        $data->RemittanceIdentifier = 'AT1234567890XYZ';
        $data->WebshopArticles[] = $webshopArticle;
        $aSimpleXml = $data->GetSimpleXml();

        $eDom = new \DOMDocument();
        $eDom->loadXML($this->GetEpsData('TransferInitiatorDetailsWithoutSignature.xml'));
        $eDom->formatOutput = true;
        $eDom->preserveWhiteSpace = false;
        $eDom->normalizeDocument();

        $this->assertEquals($eDom->saveXML(), $aSimpleXml->asXML());
    }

    public function testGenerateTransferInitiatorDetailsWithOfiIdentifier()
    {
        $webshopArticle = new WebshopArticle("Toaster", 1, 15000);
        $transferMsgDetails = new TransferMsgDetails("http://10.18.70.8:7001/vendorconfirmation", "http://10.18.70.8:7001/transactionok?danke.asp", "http://10.18.70.8:7001/transactionnok?fehler.asp");
        $transferMsgDetails->TargetWindowNok = $transferMsgDetails->TargetWindowOk = 'Mustershop';

        $data = new TransferInitiatorDetails('AKLJS231534', 'topSecret', 'GAWIATW1XXX', 'Max Mustermann', 'AT611904300234573201', '1234567890ABCDEFG', 15000, $transferMsgDetails, '2007-03-16');
        $data->RemittanceIdentifier = 'AT1234567890XYZ';
        $data->WebshopArticles[] = $webshopArticle;
        $data->OrderingCustomerOfiIdentifier = 'TESTBANKXXX';
        $aSimpleXml = $data->GetSimpleXml();

        $eDom = new \DOMDocument();
        $eDom->loadXML($this->GetEpsData('TransferInitiatorDetailsWithoutSignatureAndOrderingCustomerOfiIdentifier.xml'));
        $eDom->formatOutput = true;
        $eDom->preserveWhiteSpace = false;
        $eDom->normalizeDocument();

        $this->assertEquals($eDom->saveXML(), $aSimpleXml->asXML());
    }

    public function testTransferInitiatorDetailsInvalidExpirationMinutes()
    {
        $transferMsgDetails = new TransferMsgDetails("http://10.18.70.8:7001/vendorconfirmation", "http://10.18.70.8:7001/transactionok?danke.asp", "http://10.18.70.8:7001/transactionnok?fehler.asp");
        $transferMsgDetails->TargetWindowNok = $transferMsgDetails->TargetWindowOk = 'Mustershop';

        $data = new TransferInitiatorDetails('AKLJS231534', 'topSecret', 'GAWIATW1XXX', 'Max Mustermann', 'AT611904300234573201', '1234567890ABCDEFG', 15000, $transferMsgDetails, '2007-03-16');

        $this->setExpectedException('InvalidArgumentException', 'Expiration minutes value of "3" is not between 5 and 60.');
        $data->SetExpirationMinutes(3);

    }

    public function testTransferInitiatorDetailsWithExpirationTime()
    {
        $transferMsgDetails = new TransferMsgDetails("http://10.18.70.8:7001/vendorconfirmation", "http://10.18.70.8:7001/transactionok?danke.asp", "http://10.18.70.8:7001/transactionnok?fehler.asp");
        $transferMsgDetails->TargetWindowNok = $transferMsgDetails->TargetWindowOk = 'Mustershop';

        $data = new TransferInitiatorDetails('AKLJS231534', 'topSecret', 'GAWIATW1XXX', 'Max Mustermann', 'AT611904300234573201', '1234567890ABCDEFG', 15000, $transferMsgDetails, '2007-03-16');
        $data->SetExpirationMinutes(5);
        $aSimpleXml = $data->GetSimpleXml();

        $actual = $aSimpleXml->asXML();
        XmlValidator::ValidateEpsProtocol($actual);
        $this->assertContains('ExpirationTime', $actual);
    }

    public function testTransferInitiatorDetailsWithUnstructuredRemittanceIdentifier()
    {
        $transferMsgDetails = new TransferMsgDetails("http://10.18.70.8:7001/vendorconfirmation", "http://10.18.70.8:7001/transactionok?danke.asp", "http://10.18.70.8:7001/transactionnok?fehler.asp");
        $transferMsgDetails->TargetWindowNok = $transferMsgDetails->TargetWindowOk = 'Mustershop';

        $data = new TransferInitiatorDetails('AKLJS231534', 'topSecret', 'GAWIATW1XXX', 'Max Mustermann', 'AT611904300234573201', '1234567890ABCDEFG', 15000, $transferMsgDetails, '2007-03-16');
        $data->UnstructuredRemittanceIdentifier = 'Foo is not Bar';
        $data->SetExpirationMinutes(5);
        $aSimpleXml = $data->GetSimpleXml();

        $actual = $aSimpleXml->asXML();
        XmlValidator::ValidateEpsProtocol($actual);
        $this->assertContains('UnstructuredRemittanceIdentifier>Foo is not Bar', $actual);
    }
}