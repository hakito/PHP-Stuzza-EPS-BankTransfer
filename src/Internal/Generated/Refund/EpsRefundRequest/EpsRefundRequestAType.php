<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Refund\EpsRefundRequest;

/**
 * Class representing EpsRefundRequestAType
 */
class EpsRefundRequestAType
{

    /**
     * @var \DateTime $creDtTm
     */
    private $creDtTm = null;

    /**
     * @var string $transactionId
     */
    private $transactionId = null;

    /**
     * @var string $merchantIBAN
     */
    private $merchantIBAN = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Refund\Amount $amount
     */
    private $amount = null;

    /**
     * @var string $refundReference
     */
    private $refundReference = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Refund\AuthenticationDetails $authenticationDetails
     */
    private $authenticationDetails = null;

    /**
     * Gets as creDtTm
     *
     * @return \DateTime
     */
    public function getCreDtTm()
    {
        return $this->creDtTm;
    }

    /**
     * Sets a new creDtTm
     *
     * @param \DateTime $creDtTm
     * @return self
     */
    public function setCreDtTm(\DateTime $creDtTm)
    {
        $this->creDtTm = $creDtTm;
        return $this;
    }

    /**
     * Gets as transactionId
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Sets a new transactionId
     *
     * @param string $transactionId
     * @return self
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * Gets as merchantIBAN
     *
     * @return string
     */
    public function getMerchantIBAN()
    {
        return $this->merchantIBAN;
    }

    /**
     * Sets a new merchantIBAN
     *
     * @param string $merchantIBAN
     * @return self
     */
    public function setMerchantIBAN($merchantIBAN)
    {
        $this->merchantIBAN = $merchantIBAN;
        return $this;
    }

    /**
     * Gets as amount
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Refund\Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets a new amount
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Refund\Amount $amount
     * @return self
     */
    public function setAmount(\Psa\EpsBankTransfer\Internal\Generated\Refund\Amount $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Gets as refundReference
     *
     * @return string
     */
    public function getRefundReference()
    {
        return $this->refundReference;
    }

    /**
     * Sets a new refundReference
     *
     * @param string $refundReference
     * @return self
     */
    public function setRefundReference($refundReference)
    {
        $this->refundReference = $refundReference;
        return $this;
    }

    /**
     * Gets as authenticationDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Refund\AuthenticationDetails
     */
    public function getAuthenticationDetails()
    {
        return $this->authenticationDetails;
    }

    /**
     * Sets a new authenticationDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Refund\AuthenticationDetails $authenticationDetails
     * @return self
     */
    public function setAuthenticationDetails(\Psa\EpsBankTransfer\Internal\Generated\Refund\AuthenticationDetails $authenticationDetails)
    {
        $this->authenticationDetails = $authenticationDetails;
        return $this;
    }


}

