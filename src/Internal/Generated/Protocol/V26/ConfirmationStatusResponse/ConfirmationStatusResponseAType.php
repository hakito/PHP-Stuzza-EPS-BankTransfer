<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusResponse;

/**
 * Class representing ConfirmationStatusResponseAType
 */
class ConfirmationStatusResponseAType
{

    /**
     * @var string $sessionId
     */
    private $sessionId = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentConfirmationDetails $paymentConfirmationDetails
     */
    private $paymentConfirmationDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ErrorDetails $errorDetails
     */
    private $errorDetails = null;

    /**
     * Gets as sessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Sets a new sessionId
     *
     * @param string $sessionId
     * @return self
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * Gets as paymentConfirmationDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentConfirmationDetails
     */
    public function getPaymentConfirmationDetails()
    {
        return $this->paymentConfirmationDetails;
    }

    /**
     * Sets a new paymentConfirmationDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentConfirmationDetails $paymentConfirmationDetails
     * @return self
     */
    public function setPaymentConfirmationDetails(?\Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentConfirmationDetails $paymentConfirmationDetails = null)
    {
        $this->paymentConfirmationDetails = $paymentConfirmationDetails;
        return $this;
    }

    /**
     * Gets as errorDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ErrorDetails
     */
    public function getErrorDetails()
    {
        return $this->errorDetails;
    }

    /**
     * Sets a new errorDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ErrorDetails $errorDetails
     * @return self
     */
    public function setErrorDetails(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ErrorDetails $errorDetails = null)
    {
        $this->errorDetails = $errorDetails;
        return $this;
    }


}

