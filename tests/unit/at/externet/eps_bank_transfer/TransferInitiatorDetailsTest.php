<?php

namespace at\externet\eps_bank_transfer;

class TransferInitiatorDetailsTest extends BaseTest
{
    public function testGenerateTransferInitiatorDetails()
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

        $this->assertEquals($eDom->saveXML(), $aSimpleXml->asXML());
    }
}