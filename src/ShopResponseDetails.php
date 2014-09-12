<?php

namespace at\externet\eps_bank_transfer;

class ShopResponseDetails
{

    /** @var string Hinweis vom Händler über den aufgetretenen Fehler */
    public $ErrorMsg;

    /** @var string die von der Bank generierte Session Kennung */
    public $SessionId;

    /** @var string der von der Bank übermittelte Status zur eps Transaktion */
    public $StatusCode;

    /** @var string die von der Bank generierte Ersterfasserreferenz */
    public $PaymentReferenceIdentifier;

    /**
     * 
     * @return SimpleXmlElement
     */
    public function GetSimpleXml()
    {
        /** @var SimpleXmlElmenet */
        $xml = EpsXmlElement::CreateEmptySimpleXml('epsp:EpsProtocolDetails xmlns:epsp="' . XMLNS_epsp . '" xmlns:atrul="http://www.stuzza.at/namespaces/eps/austrianrules/2013/02" xmlns:dsig="http://www.w3.org/2000/09/xmldsig#" xmlns:epi="' . XMLNS_epi . '" xmlns:eps="' . XMLNS_eps . '" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.stuzza.at/namespaces/eps/protocol/2013/02 EPSProtocol-V25.xsd" SessionLanguage="DE"');

        $ShopResponseDetails = $xml->addChildExt('ShopResponseDetails', '', 'epsp');

       if (!empty($this->ErrorMsg))
        {
            if (!empty($this->ErrorMsg))
                $ShopResponseDetails->addChildExt('ErrorMsg', $this->ErrorMsg, 'epsp');
        }
        else
        {
            if (!empty($this->SessionId))
                $ShopResponseDetails->addChildExt('SessionId', $this->SessionId, 'epsp');

            $ShopConfirmationDetails = $ShopResponseDetails->addChildExt('ShopConfirmationDetails', '', 'eps');
            $ShopConfirmationDetails->addChildExt('StatusCode', $this->StatusCode, 'eps');
            if (isset($this->PaymentReferenceIdentifier))
                $ShopConfirmationDetails->addChildExt('PaymentReferenceIdentifier', $this->PaymentReferenceIdentifier, 'eps');
        }
        return $xml;
    }

}