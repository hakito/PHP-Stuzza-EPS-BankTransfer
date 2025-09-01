<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Payment\V26\ShopConfirmationDetails;

/**
 * Class representing ShopConfirmationDetailsAType
 */
class ShopConfirmationDetailsAType
{

    /**
     * @var string $statusCode
     */
    private $statusCode = null;

    /**
     * @var string $paymentReferenceIdentifier
     */
    private $paymentReferenceIdentifier = null;

    /**
     * Gets as statusCode
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets a new statusCode
     *
     * @param string $statusCode
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Gets as paymentReferenceIdentifier
     *
     * @return string
     */
    public function getPaymentReferenceIdentifier()
    {
        return $this->paymentReferenceIdentifier;
    }

    /**
     * Sets a new paymentReferenceIdentifier
     *
     * @param string $paymentReferenceIdentifier
     * @return self
     */
    public function setPaymentReferenceIdentifier($paymentReferenceIdentifier)
    {
        $this->paymentReferenceIdentifier = $paymentReferenceIdentifier;
        return $this;
    }


}

