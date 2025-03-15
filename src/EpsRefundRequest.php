<?php

namespace at\externet\eps_bank_transfer;

class EpsRefundRequest
{

    /**
     * @var string ISO 8601 datetime string for the creation time (e.g., "2025-02-10T15:30:00")
     */
    public $CreDtTm;

    /**
     * @var string Transaction identifier (1 to 36 characters: [a-zA-Z0-9-._~])
     */
    public $TransactionId;

    /**
     * @var string Merchant IBAN (must follow the IBAN pattern, up to 34 characters)
     */
    public $MerchantIBAN;

    /**
     * @var float|string Monetary amount for the refund
     */
    public $Amount;

    /**
     * @var string Currency code (3 uppercase letters) for the amount.
     *             This value is set as an attribute on the Amount element.
     */
    public $AmountCurrencyIdentifier;

    /**
     * @var string|null Optional refund reference (max 35 characters).
     */
    public $RefundReference;

    /**
     * @var string User ID (max 25 characters).
     */
    public $UserId;

    /**
     * @var string PIN value for authentication.
     */
    public $Pin;

    /**
     * Generates the SimpleXML representation of the EpsRefundRequest.
     *
     * @return SimpleXMLElement The XML element representing the refund request.
     */
    public function __construct(
        string  $CreDtTm,
        string  $TransactionId,
        string  $MerchantIBAN,
                $Amount,
        string  $AmountCurrencyIdentifier,
        string  $UserId,
        string  $Pin,
        ?string $RefundReference = null
    )
    {
        $this->CreDtTm = $CreDtTm;
        $this->TransactionId = $TransactionId;
        $this->MerchantIBAN = $MerchantIBAN;
        $this->Amount = $Amount;
        $this->AmountCurrencyIdentifier = $AmountCurrencyIdentifier;
        $this->RefundReference = $RefundReference;
        $this->UserId = $UserId;
        $this->Pin = $Pin;
    }

    public function GetSimpleXml()
    {
        // Create an empty XML document with the root element and proper namespaces.
        // The root element is "epsr:EpsRefundRequest" in the refund namespace.
        $xml = EpsXmlElement::CreateEmptySimpleXml(
            'epsr:EpsRefundRequest xmlns:epsr="http://www.stuzza.at/namespaces/eps/refund/2018/09" xmlns:dsig="http://www.w3.org/2000/09/xmldsig#"'
        );

        // Add the mandatory elements:
        $xml->addChildExt('CreDtTm', $this->CreDtTm, 'epsr');
        $xml->addChildExt('TransactionId', $this->TransactionId, 'epsr');
        $xml->addChildExt('MerchantIBAN', $this->MerchantIBAN, 'epsr');

        // Create the Amount element with its simple content and add the required attribute.
        $amountElement = $xml->addChildExt('Amount', $this->Amount, 'epsr');
        $amountElement->addAttribute('AmountCurrencyIdentifier', $this->AmountCurrencyIdentifier);

        // Add the optional RefundReference element if it is set.
        if (!empty($this->RefundReference)) {
            $xml->addChildExt('RefundReference', $this->RefundReference, 'epsr');
        }

        // Build the AuthenticationDetails element.
        $authElement = $xml->addChildExt('AuthenticationDetails', '', 'epsr');
        $authElement->addChildExt('UserId', $this->UserId, 'epsr');

        // Add hardcoded authentication details.
        $authElement->addChildExt(
            'SHA256Fingerprint',
            $this->generateSHA256Fingerprint(
                $this->Pin,
                $this->CreDtTm,
                $this->TransactionId,
                $this->MerchantIBAN,
                $this->Amount,
                $this->AmountCurrencyIdentifier,
                $this->UserId,
                $this->RefundReference
            ),
            'epsr'
        );

        return $xml;
    }

    private function generateSHA256Fingerprint($pin, $creationDateTime, $transactionId, $merchantIban, $amountValue, $amountCurrency, $userId, $refundReference = null)
    {
        $inputData = $pin .
            $creationDateTime .
            $transactionId .
            $merchantIban .
            $amountValue .
            $amountCurrency .
            $refundReference .
            $userId;

        return strtoupper(hash('sha256', $inputData));
    }
}