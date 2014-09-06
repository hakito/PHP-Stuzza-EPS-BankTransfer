<?php

namespace at\externet\eps_bank_transfer;
require_once "functions.php";

/**
 * eps Zahlungsauftragsnachricht
 */
class TransferInitiatorDetails
{

    /**
     * Identifikation des Geschäftspartners dank UserID (= Händler-ID), die er von einer eps Bank ausgestellt bekommt
     * @var string
     */
    public $UserId;

    /**
     * Secret given by bank
     * @var string
     */
    public $Secret;

    /**
     * Erstellungsdatum des Zahlungsauftrags (xsd::date)
     * @var string
     */
    public $Date;

    /**
     * ISO 9362 Bank Identifier Code (BIC), Bankcode zur Identifikation einer Bank
     * @var string
     */
    public $BfiBicIdentifier;

    /**
     * Identifikation des Begünstigten (Name und Adresse) in unstrukturierter Form, wobei der Begünstigte nicht mit dem Kontoinhaber ident sein muss.
     * @var string
     */
    public $BeneficiaryNameAddressText;

    /**
     * Angabe der Kontoverbindung des Begünstigten durch Angabe der IBAN (International Bank Account Number) - Kontonummer des Begünstigten, z.B. AT611904300234573201 (11-stellige Konto-nummer: 00234573201)
     * @var string
     */
    public $BeneficiaryAccountIdentifier;

    /**
     * Referenz der Zahlungsauftragsnachricht, z.B. für Nachforschungszwecke beim Händler
     * @var string
     */
    public $ReferenceIdentifier;

    /**
     * Zahlungsreferenz Eindeutige Referenz des Händlers (= Begünstigter) zu einem Geschäftsfall, der im Zahlungsverkehr unverändert wieder an den Händler zurückgeleitet werden muss 
     * @var string 
     */
    public $RemittanceIdentifier;

    /**
     * min-/max. Durchführungszeitpunkt für eps Zahlung
     * @var string
     */
    public $ExpirationTime;

    /**
     * Bei Angabe von Cent-Werten müssen diese vom Euro-Betrag mit einem Punkt ge-trennt übermittelt werden, z.B. 150.55 (NICHT 150,55)!
     * @var string
     */
    public $InstructedAmount;

    /**
     * Angabe der Währung gem. ISO 4217
     * @var string
     */
    public $AmountCurrencyIdentifier = 'EUR';

    /**
     * Array of webshop articles
     * @var WebshopArticle[]
     */
    public $WebshopArticles;

    /**
     * Händler Angabe relevanter URL Adressen
     * @var TransferMsgDetails
     */
    public $TransferMsgDetails;

    /**
     * Optional Angabe der Bankverbindung/BIC des Zahlungspflichtigen / Käufer
     * @var string
     */
    public $OrderingCustomerOfiIdentifier;

    /**
     *
     * @param string $userId
     * @param string $secret
     * @param string $bfiBicIdentifier
     * @param string $beneficiaryNameAddressText
     * @param string $beneficiaryAccountIdentifier
     * @param string $referenceIdentifier
     * @param string $instructedAmount in cents
     * @param TransferMsgDetails $transferMsgDetails
     * @param string $date
     */
    public function __construct($userId, $secret, $bfiBicIdentifier, $beneficiaryNameAddressText, $beneficiaryAccountIdentifier, $referenceIdentifier, $instructedAmount, $transferMsgDetails, $date = null)
    {
        $this->UserId = $userId;
        $this->Secret = $secret;
        $this->BfiBicIdentifier = $bfiBicIdentifier;
        $this->BeneficiaryNameAddressText = $beneficiaryNameAddressText;
        $this->BeneficiaryAccountIdentifier = $beneficiaryAccountIdentifier;
        $this->ReferenceIdentifier = $referenceIdentifier;
        $this->SetInstructedAmount($instructedAmount);
        $this->WebshopArticles = Array();
        $this->TransferMsgDetails = $transferMsgDetails;

        $this->Date = $date == null ? date("Y-m-d") : $date;
    }

    /**
     * Sets ExpirationTime by adding given amount of minutes to the current
     * timestamp.
     * @param int $minutes Must be between 5 and 60
     * @throws \InvalidArgumentException if minutes not between 5 and 60
     */
    public function SetExpirationMinutes($minutes)
    {
        if ($minutes < 5 || $minutes > 60)
            throw new \InvalidArgumentException('Expiration minutes value of "' . $minutes . '" is not between 5 and 60.');

        $expires = new \DateTime();
        $expires->add(new \DateInterval('PT' . $minutes . 'M'));
        $this->ExpirationTime = $expires->format(DATE_RFC3339);
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
        $input = $this->Secret . $this->Date . $this->ReferenceIdentifier . $this->BeneficiaryAccountIdentifier
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
        $xml = EpsXmlElement::CreateEmptySimpleXml('epsp:EpsProtocolDetails SessionLanguage="DE" xsi:schemaLocation="http://www.stuzza.at/namespaces/eps/protocol/2013/02 EPSProtocol-V25.xsd" xmlns:atrul="http://www.stuzza.at/namespaces/eps/austrianrules/2013/02" xmlns:epi="http://www.stuzza.at/namespaces/eps/epi/2013/02" xmlns:eps="http://www.stuzza.at/namespaces/eps/payment/2013/02" xmlns:epsp="http://www.stuzza.at/namespaces/eps/protocol/2013/02" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"');

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

        if (!empty($this->WebshopArticles))
        {
            $WebshopDetails = $TransferInitiatorDetails->addChildExt('WebshopDetails', '', 'epsp');

            foreach ($this->WebshopArticles as $article)
            {
                $WebshopArticle = $WebshopDetails->addChildExt('WebshopArticle', '', 'epsp');
                $WebshopArticle->addAttribute('ArticleName', $article->Name);
                $WebshopArticle->addAttribute('ArticleCount', $article->Count);
                $WebshopArticle->addAttribute('ArticlePrice', $article->Price);
            }
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

        if (!empty($this->OrderingCustomerOfiIdentifier))
            $IdentificationDetails->addChildExt('OrderingCustomerOfiIdentifier', $this->OrderingCustomerOfiIdentifier, 'epi');

        $AustrianRulesDetails = $PaymentInitiatorDetails->addChildExt('AustrianRulesDetails', '', 'atrul');
        $AustrianRulesDetails->addChildExt('DigSig', 'SIG', 'atrul');

        if (!empty($this->ExpirationTime))
            $AustrianRulesDetails->AddChildExt('ExpirationTime', $this->ExpirationTime, 'atrul');

        return $xml;
    }
}

?>
