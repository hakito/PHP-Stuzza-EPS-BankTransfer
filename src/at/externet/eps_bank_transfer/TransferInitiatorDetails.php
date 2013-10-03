<?php

namespace at\externet\eps_bank_transfer;
require_once "functions.php";

class TransferInitiatorDetails
{

    /** @var string Identifikation des Geschäftspartners dank UserID (= Händler-ID), die er von einer eps Bank ausgestellt bekommt */
    public $UserId;

    /** @var string */
    public $Secret;

    /** @var string Erstellungsdatum des Zahlungsauftrags (xsd::date) */
    public $date;

    /** @var string ISO 9362 Bank Identifier Code (BIC), Bankcode zur Identifikation einer Bank */
    public $BfiBicIdentifier;

    /** @var string Identifikation des Begünstigten (Name und Adresse) in unstruktu-rierter Form, wobei der Begünstigte nicht mit dem Kontoinhaber ident sein muss. */
    public $BeneficiaryNameAddressText;

    /** @var string Angabe der Kontoverbindung des Begünstigten durch Angabe der IBAN (International Bank Account Number) - Kontonummer des Begünstigten, z.B. AT611904300234573201 (11-stellige Konto-nummer: 00234573201) */
    public $BeneficiaryAccountIdentifier;

    /** @var string Referenz der Zahlungsauftragsnachricht, z.B. für Nachforschungs-zwecke beim Händler */
    public $ReferenceIdentifier;

    /**
     * Zahlungsreferenz Eindeutige Referenz des Händlers (= Begünstigter) zu einem Geschäftsfall, der im Zahlungsverkehr unverändert wieder an den Händler zurückgeleitet werden muss 
     * @var string 
     */
    public $RemittanceIdentifier;

    /** @var string Bei Angabe von Cent-Werten müssen diese vom Euro-Betrag mit einem Punkt ge-trennt übermittelt werden, z.B. 150.55 (NICHT 150,55)! */
    public $InstructedAmount;
    public $AmountCurrencyIdentifier = 'EUR';

    /** @var WebshopArticle array */
    public $WebshopArticles;

    /** @var TransferMsgDetails */
    public $TransferMsgDetails;

    /**
     *
     * @param string $userId
     * @param string $secret
     * @param string $bfiBicIdentifier
     * @param string $beneficiaryNameAddressText
     * @param string $beneficiaryAccountIdentifier
     * @param string $referenceIdentifier
     * @param string $remittanceIdentifier
     * @param string $instructedAmount in cents
     * @param WebshopArticle[] $webshopArticles
     * @param TransferMsgDetails $transferMsgDetails
     * @param string $date
     */
    public function __construct($userId, $secret, $bfiBicIdentifier, $beneficiaryNameAddressText, $beneficiaryAccountIdentifier, $referenceIdentifier, $remittanceIdentifier, $instructedAmount, $webshopArticles, $transferMsgDetails, $date = null)
    {
        $this->UserId = $userId;
        $this->Secret = $secret;
        $this->BfiBicIdentifier = $bfiBicIdentifier;
        $this->BeneficiaryNameAddressText = $beneficiaryNameAddressText;
        $this->BeneficiaryAccountIdentifier = $beneficiaryAccountIdentifier;
        $this->ReferenceIdentifier = $referenceIdentifier;
        $this->RemittanceIdentifier = $remittanceIdentifier;
        $this->SetInstructedAmount($instructedAmount);
        if (is_array($webshopArticles))
            $this->WebshopArticles = $webshopArticles;
        else
        {
            $this->WebshopArticles = Array();
            $this->WebshopArticles[] = $webshopArticles;
        }
        $this->TransferMsgDetails = $transferMsgDetails;

        $this->Date = $date == null ? date("Y-m-d") : $date;
    }

    /**
     * 
     * @param int $amount in cents
     */
    public function SetInstructedAmount($amount)
    {
        $this->InstructedAmount = FormatMonetaryXsdDecimal($amount);
    }

    public function GetMD5Fingerprint()
    {
        $input = $this->Secret . $this->date . $this->ReferenceIdentifier . $this->BeneficiaryAccountIdentifier
                . $this->RemittanceIdentifier . $this->InstructedAmount . $this->AmountCurrencyIdentifier
                . $this->UserId;

        return md5($input);
    }

    /**
     * 
     * @return SimpleXMLElement
     */
    public function GetSimpleXml()
    {

        /** @var SimpleXmlElmenet */
        $xml = EpsXmlElement::CreateEmptySimpleXml('epsp:EpsProtocolDetails xmlns:epsp="http://www.stuzza.at/namespaces/eps/protocol/2011/11" xmlns:atrul="http://www.stuzza.at/namespaces/eps/austrianrules/2011/11" xmlns:dsig="http://www.w3.org/2000/09/xmldsig#" xmlns:epi="http://www.stuzza.at/namespaces/eps/epi/2011/11" xmlns:eps="http://www.stuzza.at/namespaces/eps/payment/2011/11" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.stuzza.at/namespaces/eps/protocol/2011/11 EPSProtocol-V24.xsd" SessionLanguage="DE"');

        $TransferInitiatorDetails = $xml->addChildExt('TransferInitiatorDetails', '', 'epsp');

        $PaymentInitiatorDetails = $TransferInitiatorDetails->addChildExt('PaymentInitiatorDetails', '', 'eps');
        $TransferMsgDetails = $TransferInitiatorDetails->addChildExt('TransferMsgDetails', '', 'epsp');
        $TransferMsgDetails->addChildExt('ConfirmationUrl', $this->TransferMsgDetails->ConfirmationUrl, 'epsp');
        $TransactionOkUrl = $TransferMsgDetails->addChildExt('TransactionOkUrl', $this->TransferMsgDetails->TransactionOkUrl, 'epsp');
        $TransactionNokUrl = $TransferMsgDetails->addChildExt('TransactionNokUrl', $this->TransferMsgDetails->TransactionNokUrl, 'epsp');

        if (!empty($this->TransferMsgDetails->TargetWindowOk))            
            $TransactionOkUrl->addAttribute('TargetWindow', $this->TransferMsgDetails->TargetWindowOk);

        if (!empty($this->TransferMsgDetails->TargetWindowNok))            
            $TransactionNokUrl->addAttribute('TargetWindow', $this->TransferMsgDetails->TargetWindowNok);

        $WebshopDetails = $TransferInitiatorDetails->addChildExt('WebshopDetails', '', 'epsp');

        foreach ($this->WebshopArticles as $article)
        {
            $WebshopArticle = $WebshopDetails->addChildExt('WebshopArticle', '', 'epsp');
            $WebshopArticle->addAttribute('ArticleName', $article->Name);
            $WebshopArticle->addAttribute('ArticleCount', $article->Count);
            $WebshopArticle->addAttribute('ArticlePrice', $article->Price);
        }

        $AuthenticationDetails = $TransferInitiatorDetails->addChildExt('AuthenticationDetails', '', 'epsp');
        $AuthenticationDetails->addChildExt('UserId', $this->UserId, 'epsp');
        $AuthenticationDetails->addChildExt('MD5Fingerprint', $this->GetMD5Fingerprint(), 'epsp');
        $EpiDetails = $PaymentInitiatorDetails->addChildExt('EpiDetails', '', 'epi');
        $IdentificationDetails = $EpiDetails->addChildExt("IdentificationDetails", '', 'epi');
        $PartyDetails = $EpiDetails->addChildExt('PartyDetails', '', 'epi');
        $PaymentInstructionDetails = $EpiDetails->addChildExt('PaymentInstructionDetails', '', 'epi');
        $PaymentInstructionDetails->addChildExt('RemittanceIdentifier', $this->RemittanceIdentifier, 'epi');
        $InstructedAmount = $PaymentInstructionDetails->addChildExt('InstructedAmount', $this->InstructedAmount, 'epi');
        $InstructedAmount->addAttribute('AmountCurrencyIdentifier', $this->AmountCurrencyIdentifier);
        $PaymentInstructionDetails->addChildExt('ChargeCode', 'SHA', 'epi');

        $BfiPartyDetails = $PartyDetails->addChildExt('BfiPartyDetails', '', 'epi');
        $BfiPartyDetails->addChildExt('BfiBicIdentifier', $this->BfiBicIdentifier, 'epi');
        $BeneficiaryPartyDetails = $PartyDetails->addChildExt('BeneficiaryPartyDetails', '', 'epi');
        $BeneficiaryPartyDetails->addChildExt('BeneficiaryNameAddressText', $this->BeneficiaryNameAddressText, 'epi');
        $BeneficiaryPartyDetails->addChildExt('BeneficiaryAccountIdentifier', $this->BeneficiaryAccountIdentifier, 'epi');
        $IdentificationDetails->addChildExt('Date', $this->Date, 'epi');
        $IdentificationDetails->addChildExt('ReferenceIdentifier', $this->ReferenceIdentifier, 'epi');

        $AustrianRulesDetails = $PaymentInitiatorDetails->addChildExt('AustrianRulesDetails', '', 'atrul');
        $AustrianRulesDetails->addChildExt('DigSig', 'SIG', 'atrul');

        return $xml;
    }

    private static function GetMoneyValue($val)
    {
        if (is_string($amount))
            return $amount;
        return sprintf("%01.2f", $val);
    }

}

?>
