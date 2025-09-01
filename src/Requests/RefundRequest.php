<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Requests;
use Exception;
use Psa\EpsBankTransfer\Internal\Generated\Refund\Amount;
use Psa\EpsBankTransfer\Internal\Generated\Refund\AuthenticationDetails;
use Psa\EpsBankTransfer\Internal\Generated\Refund\EpsRefundRequest;
use Psa\EpsBankTransfer\Utilities\Fingerprint;

class RefundRequest
{
    /**
     * @var string Creation timestamp in ISO 8601 format
     */
    public $creDtTm;

    /**
     * @var string Transaction identifier (1-36 chars: [a-zA-Z0-9-._~])
     */
    public $transactionId;

    /**
     * @var string Merchant IBAN (up to 34 chars)
     */
    public $merchantIban;

    /**
     * @var string Refund amount as decimal (e.g., "1.00").
     */
    public $amount;

    /**
     * @var string Three-letter currency code
     */
    public $amountCurrencyIdentifier;

    /**
     * @var string|null Reference ID for refund (max 35 chars)
     */
    public $refundReference;

    /**
     * @var string User ID (max 25 chars)
     */
    public $userId;

    /**
     * @var string Authentication PIN
     */
    public $pin;

    public function __construct(
        string  $creDtTm,
        string  $transactionId,
        string  $merchantIban,
                $amount,
        string  $amountCurrencyIdentifier,
        string  $userId,
        string  $pin,
        ?string $refundReference = null
    )
    {
        $this->creDtTm = $creDtTm;
        $this->transactionId = $transactionId;
        $this->merchantIban = $merchantIban;
        $this->amount = $amount;
        $this->amountCurrencyIdentifier = $amountCurrencyIdentifier;
        $this->refundReference = $refundReference;
        $this->userId = $userId;
        $this->pin = $pin;
    }

    /**
     * Domain to EPS schema mapping (Refund v2.6).
     *
     * Populates EpsRefundRequest from the domain RefundRequest. Amount must be a decimal string
     * (e.g., "1.00"). Currency must be ISO 4217 (EPS refund supports EUR).
     *
     * @return EpsRefundRequest Fully populated refund request.
     * @throws Exception When date parsing fails or invalid values are given.
     */
    public function toV26(): EpsRefundRequest
    {
        $refundRequest = new EpsRefundRequest();

        $refundRequest->setCreDtTm(new \DateTime($this->creDtTm));
        $refundRequest->setTransactionId($this->transactionId);
        $refundRequest->setMerchantIBAN($this->merchantIban);

        $amount = new Amount($this->amount);
        $amount->setAmountCurrencyIdentifier($this->amountCurrencyIdentifier);
        $refundRequest->setAmount($amount);

        if (!empty($this->refundReference)) {
            $refundRequest->setRefundReference($this->refundReference);
        }

        $auth = new AuthenticationDetails();
        $auth->setUserId($this->userId);

        $fingerprint = Fingerprint::generateSHA256Fingerprint(
            $this->pin,
            $this->creDtTm,
            $this->transactionId,
            $this->merchantIban,
            $this->amount,
            $this->amountCurrencyIdentifier,
            $this->userId,
            $this->refundReference
        );
        $auth->setSHA256Fingerprint($fingerprint);

        $refundRequest->setAuthenticationDetails($auth);

        return $refundRequest;
    }

}