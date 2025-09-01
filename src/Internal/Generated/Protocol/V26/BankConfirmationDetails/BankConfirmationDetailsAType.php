<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankConfirmationDetails;

/**
 * Class representing BankConfirmationDetailsAType
 */
class BankConfirmationDetailsAType
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
    public function setPaymentConfirmationDetails(\Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentConfirmationDetails $paymentConfirmationDetails)
    {
        $this->paymentConfirmationDetails = $paymentConfirmationDetails;
        return $this;
    }


}

