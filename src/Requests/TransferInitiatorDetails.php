<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Requests;

use DateInterval;
use DateTime;
use Exception;
use Psa\EpsBankTransfer\Internal\Generated\AustrianRules\AustrianRulesDetails;
use Psa\EpsBankTransfer\Internal\Generated\Epi\BeneficiaryPartyDetails;
use Psa\EpsBankTransfer\Internal\Generated\Epi\BfiPartyDetails;
use Psa\EpsBankTransfer\Internal\Generated\Epi\EpiDetails;
use Psa\EpsBankTransfer\Internal\Generated\Epi\IdentificationDetails;
use Psa\EpsBankTransfer\Internal\Generated\Epi\InstructedAmount;
use Psa\EpsBankTransfer\Internal\Generated\Epi\PartyDetails;
use Psa\EpsBankTransfer\Internal\Generated\Epi\PaymentInstructionDetails;
use Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentInitiatorDetails;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\AuthenticationDetails;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\EpsProtocolDetails;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionNokUrl;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionOkUrl;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransferInitiatorDetails as V6TransferInitiatorDetails;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransferMsgDetails;
use Psa\EpsBankTransfer\Requests\Parts\PaymentFlowUrls;
use Psa\EpsBankTransfer\Requests\Parts\WebshopArticle;
use Psa\EpsBankTransfer\Utilities\MoneyFormatter;
use InvalidArgumentException;

/**
 * EPS payment order message
 */
class TransferInitiatorDetails
{

    /**
     * Business partner identification through UserID (= Merchant ID) issued by an eps bank
     * @var string
     */
    private $userId;

    /**
     * Secret given by bank
     * @var string
     */
    private $secret;

    /**
     * Creation date of payment order (xsd::date)
     * @var string
     */
    private $date;

    /**
     * ISO 9362 Bank Identifier Code (BIC) for bank identification
     * @var string
     */
    private $bfiBicIdentifier;

    /**
     * Identification of beneficiary (name and address) in unstructured form. Beneficiary does not need to match account holder.
     * @var string
     */
    private $beneficiaryNameAddressText;

    /**
     * Beneficiary's account details specified by IBAN (International Bank Account Number), e.g. AT611904300234573201 (11-digit account number: 00234573201)
     * @var string
     */
    private $beneficiaryAccountIdentifier;

    /**
     * Payment order message reference, e.g., for merchant research purposes
     * @var string
     */
    private $referenceIdentifier;

    /**
     *
     * @var string|null
     */
    private $unstructuredRemittanceIdentifier;

    /**
     * Unique merchant reference (= beneficiary) for a business transaction that must be returned unchanged to the merchant in payment transactions
     * @var string|null
     */
    private $remittanceIdentifier;

    /**
     * Min/max execution time for eps payment
     * @var string
     */
    private $expirationTime;

    /**
     * For cent values, they must be transmitted separated from the euro amount by a period, e.g. 150.55 (NOT 150,55)!
     * @var string
     */
    private $instructedAmount;

    /**
     * Currency specification according to ISO 4217
     * @var string
     */
    private $amountCurrencyIdentifier = 'EUR';

    /**
     * Array of webshop articles
     * @var WebshopArticle[]
     */
    private $webshopArticles;

    /**
     * Merchant specification of relevant URL addresses
     * @var PaymentFlowUrls
     */
    private $transferMsgDetails;

    /**
     * Optional specification of bank details/BIC of the payment obligor / buyer
     * @var string
     */
    private $orderingCustomerOfiIdentifier;

    /**
     * @param string $userId User ID (epsp:UserId)
     * @param string $secret Merchant PIN/secret used in MD5 fingerprint (not transmitted directly)
     * @param string $bfiBicIdentifier BIC of beneficiary bank (epi:BfiBicIdentifier)
     * @param string $beneficiaryNameAddressText Beneficiary name/address (<=140 chars; banks often guarantee 70)
     * @param string $beneficiaryAccountIdentifier Beneficiary IBAN (epi:BeneficiaryAccountIdentifier)
     * @param string $referenceIdentifier Reference Identifier (epi:ReferenceIdentifier)
     * @param int|string $instructedAmount Amount in euro cents (e.g., 9999 = €99.99)
     * @param PaymentFlowUrls $transferMsgDetails Confirmation/redirect URLs (epsp:TransferMsgDetails)
     * @param string|null $date Optional date in YYYY-MM-DD (default: today)
     */

    /**
     * @var int Length of the suffix for obscurity
     */
    public $obscuritySuffixLength;

    /**
     * @var string|null Seed value used for obscurity
     */
    public $obscuritySeed;

    public function __construct(string          $userId,
                                string          $secret,
                                string          $bfiBicIdentifier,
                                string          $beneficiaryNameAddressText,
                                string          $beneficiaryAccountIdentifier,
                                string          $referenceIdentifier,
                                                $instructedAmount,
                                PaymentFlowUrls $transferMsgDetails,
                                string          $date = null,
                                int $obscuritySuffixLength = 0,
                                ?string $obscuritySeed = null)
    {
        $this->userId = $userId;
        $this->secret = $secret;
        $this->bfiBicIdentifier = $bfiBicIdentifier;
        $this->beneficiaryNameAddressText = $beneficiaryNameAddressText;
        $this->beneficiaryAccountIdentifier = $beneficiaryAccountIdentifier;
        $this->referenceIdentifier = $referenceIdentifier;
        $this->setInstructedAmount($instructedAmount);
        $this->webshopArticles = array();
        $this->transferMsgDetails = $transferMsgDetails;

        $this->date = $date == null ? date("Y-m-d") : $date;
        $this->obscuritySuffixLength = $obscuritySuffixLength;
        $this->obscuritySeed = $obscuritySeed;
    }

    /**
     * Sets ExpirationTime by adding a given number of minutes to the current
     * timestamp.
     * @param int $minutes Must be between 5 and 60
     * @throws InvalidArgumentException|Exception if minutes not between 5 and 60
     */
    public function setExpirationMinutes(int $minutes)
    {
        if ($minutes < 5 || $minutes > 60)
            throw new InvalidArgumentException('Expiration minutes value of "' . $minutes . '" is not between 5 and 60.');

        $expires = new DateTime();
        $expires->add(new DateInterval('PT' . $minutes . 'M'));
        $this->expirationTime = $expires->format(DATE_RFC3339);
    }

    /**
     * Set the instructed amount.
     *
     * @param int|string $amount Amount in euro cents (e.g., 9999 = €99.99). Must be integer-like.
     */
    public function setInstructedAmount($amount)
    {
        $this->instructedAmount = MoneyFormatter::formatXsdDecimal($amount);
    }

    public function getMD5Fingerprint(): string
    {
        $remittanceIdentifier = $this->unstructuredRemittanceIdentifier ?: $this->remittanceIdentifier;

        $input = $this->secret . $this->date . $this->referenceIdentifier . $this->beneficiaryAccountIdentifier
            . $remittanceIdentifier . $this->instructedAmount . $this->amountCurrencyIdentifier
            . $this->userId;

        return md5($input);
    }

    /**
     * Domain to EPS schema mapping (v2.6).
     *
     * Creates and populates an EpsProtocolDetails tree from the current
     * TransferInitiatorDetails. All values must adhere to EPS v2.6 rules:
     * - InstructedAmount as decimal string (formatted from euro-cents input)
     * - Exactly one of RemittanceIdentifier or UnstructuredRemittanceIdentifier may be set
     * - MD5Fingerprint derived from merchant secret and core fields
     *
     * @return EpsProtocolDetails Fully populated protocol details ready to serialize.
     * @throws Exception When date/time parsing or constraints fail.
     */
    public function toV26(): EpsProtocolDetails
    {
        $xml = new EpsProtocolDetails();
        $xml->setSessionLanguage("DE");

        $transferInitiatorDetails = new V6TransferInitiatorDetails();
        $xml->setTransferInitiatorDetails($transferInitiatorDetails);

        $paymentInitiatorDetails = new PaymentInitiatorDetails();
        $transferInitiatorDetails->setPaymentInitiatorDetails($paymentInitiatorDetails);

        $transferMsgDetails = new TransferMsgDetails();
        $transferMsgDetails->setConfirmationUrl($this->transferMsgDetails->getConfirmationUrl());

        $transactionOkUrl = new TransactionOkUrl($this->transferMsgDetails->getTransactionOkUrl());
        $transferMsgDetails->setTransactionOkUrl($transactionOkUrl);
        
        $transactionNokUrl = new TransactionNokUrl($this->transferMsgDetails->getTransactionNokUrl());
        $transferMsgDetails->setTransactionNokUrl($transactionNokUrl);
        
        $transferInitiatorDetails->setTransferMsgDetails($transferMsgDetails);

        if (!empty($this->webshopArticles)) {
            $articles = [];
            foreach ($this->webshopArticles as $article) {
                $webshopArticle = new \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\WebshopArticle();
                $webshopArticle->setArticleName($article->name);
                $webshopArticle->setArticleCount($article->count);
                $webshopArticle->setArticlePrice($article->price);

                $articles[] = $webshopArticle;
            }
            $transferInitiatorDetails->setWebshopDetails($articles);
        }

        $authenticationDetails = new AuthenticationDetails();
        $authenticationDetails->setUserId($this->userId);
        $authenticationDetails->setMD5Fingerprint($this->getMD5Fingerprint());
        $transferInitiatorDetails->setAuthenticationDetails($authenticationDetails);

        $epiDetails = new EpiDetails();
        $identificationDetails = new IdentificationDetails();
        $partyDetails = new PartyDetails();
        $paymentInstructionDetails = new PaymentInstructionDetails();

        if ($this->unstructuredRemittanceIdentifier == null) {
            $paymentInstructionDetails->setRemittanceIdentifier($this->remittanceIdentifier);
        } else {
            $paymentInstructionDetails->setUnstructuredRemittanceIdentifier($this->unstructuredRemittanceIdentifier);
        }

        $instructedAmount = new InstructedAmount($this->instructedAmount);
        $instructedAmount->setAmountCurrencyIdentifier($this->amountCurrencyIdentifier);
        $paymentInstructionDetails->setInstructedAmount($instructedAmount);
        $paymentInstructionDetails->setChargeCode('SHA');

        $bfiPartyDetails = new BfiPartyDetails();
        $bfiPartyDetails->setBfiBicIdentifier($this->bfiBicIdentifier);
        $partyDetails->setBfiPartyDetails($bfiPartyDetails);

        $beneficiaryPartyDetails = new BeneficiaryPartyDetails();
        $beneficiaryPartyDetails->setBeneficiaryNameAddressText($this->beneficiaryNameAddressText);
        $beneficiaryPartyDetails->setBeneficiaryAccountIdentifier($this->beneficiaryAccountIdentifier);
        $partyDetails->setBeneficiaryPartyDetails($beneficiaryPartyDetails);

        $identificationDetails->setDate($this->date);
        $identificationDetails->setReferenceIdentifier($this->referenceIdentifier);

        if (!empty($this->orderingCustomerOfiIdentifier)) {
            $identificationDetails->setOrderingCustomerOfiIdentifier($this->orderingCustomerOfiIdentifier);
        }

        $epiDetails->setIdentificationDetails($identificationDetails);
        $epiDetails->setPartyDetails($partyDetails);
        $epiDetails->setPaymentInstructionDetails($paymentInstructionDetails);

        $paymentInitiatorDetails->setEpiDetails($epiDetails);

        $austrianRulesDetails = new AustrianRulesDetails();
        $austrianRulesDetails->setDigSig('SIG');

        if (!empty($this->expirationTime)) {
            $austrianRulesDetails->setExpirationTime(new DateTime($this->expirationTime));
        }

        $paymentInitiatorDetails->setAustrianRulesDetails($austrianRulesDetails);

        return $xml;
    }

    public function getWebshopArticles(): array
    {
        return $this->webshopArticles;
    }

    public function setWebshopArticles(array $webshopArticles): void
    {
        $this->webshopArticles = $webshopArticles;
    }

    public function addArticle(WebshopArticle $article): void
    {
        $this->webshopArticles[] = $article;
    }

    /**
     * @param string $remittanceIdentifier
     */
    public function setRemittanceIdentifier(string $remittanceIdentifier): void
    {
        $this->remittanceIdentifier = $remittanceIdentifier;
    }

    /**
     * @param string $unstructuredRemittanceIdentifier
     */
    public function setUnstructuredRemittanceIdentifier(string $unstructuredRemittanceIdentifier): void
    {
        $this->unstructuredRemittanceIdentifier = $unstructuredRemittanceIdentifier;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return false|string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param false|string $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getBfiBicIdentifier(): string
    {
        return $this->bfiBicIdentifier;
    }

    public function setBfiBicIdentifier(string $bfiBicIdentifier): void
    {
        $this->bfiBicIdentifier = $bfiBicIdentifier;
    }

    public function getBeneficiaryNameAddressText(): string
    {
        return $this->beneficiaryNameAddressText;
    }

    public function setBeneficiaryNameAddressText(string $beneficiaryNameAddressText): void
    {
        $this->beneficiaryNameAddressText = $beneficiaryNameAddressText;
    }

    public function getBeneficiaryAccountIdentifier(): string
    {
        return $this->beneficiaryAccountIdentifier;
    }

    public function setBeneficiaryAccountIdentifier(string $beneficiaryAccountIdentifier): void
    {
        $this->beneficiaryAccountIdentifier = $beneficiaryAccountIdentifier;
    }

    public function getReferenceIdentifier(): string
    {
        return $this->referenceIdentifier;
    }

    public function setReferenceIdentifier(string $referenceIdentifier): void
    {
        $this->referenceIdentifier = $referenceIdentifier;
    }

    public function getExpirationTime(): string
    {
        return $this->expirationTime;
    }

    public function setExpirationTime(string $expirationTime): void
    {
        $this->expirationTime = $expirationTime;
    }

    public function getAmountCurrencyIdentifier(): string
    {
        return $this->amountCurrencyIdentifier;
    }

    public function setAmountCurrencyIdentifier(string $amountCurrencyIdentifier): void
    {
        $this->amountCurrencyIdentifier = $amountCurrencyIdentifier;
    }

    public function getTransferMsgDetails(): PaymentFlowUrls
    {
        return $this->transferMsgDetails;
    }

    public function setTransferMsgDetails(PaymentFlowUrls $transferMsgDetails): void
    {
        $this->transferMsgDetails = $transferMsgDetails;
    }

    public function getOrderingCustomerOfiIdentifier(): string
    {
        return $this->orderingCustomerOfiIdentifier;
    }

    public function setOrderingCustomerOfiIdentifier(string $orderingCustomerOfiIdentifier): void
    {
        $this->orderingCustomerOfiIdentifier = $orderingCustomerOfiIdentifier;
    }

    public function getObscuritySuffixLength(): int
    {
        return $this->obscuritySuffixLength;
    }

    public function setObscuritySuffixLength(int $obscuritySuffixLength): void
    {
        $this->obscuritySuffixLength = $obscuritySuffixLength;
    }

    public function getObscuritySeed(): ?string
    {
        return $this->obscuritySeed;
    }

    public function setObscuritySeed(?string $obscuritySeed): void
    {
        $this->obscuritySeed = $obscuritySeed;
    }

    /**
     * @return string
     */
    public function getRemittanceIdentifier(): ?string
    {
        return $this->remittanceIdentifier;
    }

    /**
     * @return string
     */
    public function getInstructedAmount(): string
    {
        return $this->instructedAmount;
    }

    /**
     * @return string
     */
    public function getUnstructuredRemittanceIdentifier(): ?string
    {
        return $this->unstructuredRemittanceIdentifier;
    }
}