<?php

namespace Psa\EpsBankTransfer\Internal\Generated\AustrianRules\AustrianRulesDetails;

/**
 * Class representing AustrianRulesDetailsAType
 */
class AustrianRulesDetailsAType
{

    /**
     * @var string $realization
     */
    private $realization = null;

    /**
     * @var string $paymentDescription
     */
    private $paymentDescription = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\AustrianRules\TradeCategoryDetails $tradeCategoryDetails
     */
    private $tradeCategoryDetails = null;

    /**
     * @var string $digSig
     */
    private $digSig = null;

    /**
     * @var \DateTime $expirationTime
     */
    private $expirationTime = null;

    /**
     * @var bool $statusMsgEnabled
     */
    private $statusMsgEnabled = null;

    /**
     * Gets as realization
     *
     * @return string
     */
    public function getRealization()
    {
        return $this->realization;
    }

    /**
     * Sets a new realization
     *
     * @param string $realization
     * @return self
     */
    public function setRealization($realization)
    {
        $this->realization = $realization;
        return $this;
    }

    /**
     * Gets as paymentDescription
     *
     * @return string
     */
    public function getPaymentDescription()
    {
        return $this->paymentDescription;
    }

    /**
     * Sets a new paymentDescription
     *
     * @param string $paymentDescription
     * @return self
     */
    public function setPaymentDescription($paymentDescription)
    {
        $this->paymentDescription = $paymentDescription;
        return $this;
    }

    /**
     * Gets as tradeCategoryDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\AustrianRules\TradeCategoryDetails
     */
    public function getTradeCategoryDetails()
    {
        return $this->tradeCategoryDetails;
    }

    /**
     * Sets a new tradeCategoryDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\AustrianRules\TradeCategoryDetails $tradeCategoryDetails
     * @return self
     */
    public function setTradeCategoryDetails(?\Psa\EpsBankTransfer\Internal\Generated\AustrianRules\TradeCategoryDetails $tradeCategoryDetails = null)
    {
        $this->tradeCategoryDetails = $tradeCategoryDetails;
        return $this;
    }

    /**
     * Gets as digSig
     *
     * @return string
     */
    public function getDigSig()
    {
        return $this->digSig;
    }

    /**
     * Sets a new digSig
     *
     * @param string $digSig
     * @return self
     */
    public function setDigSig($digSig)
    {
        $this->digSig = $digSig;
        return $this;
    }

    /**
     * Gets as expirationTime
     *
     * @return \DateTime
     */
    public function getExpirationTime()
    {
        return $this->expirationTime;
    }

    /**
     * Sets a new expirationTime
     *
     * @param \DateTime $expirationTime
     * @return self
     */
    public function setExpirationTime(?\DateTime $expirationTime = null)
    {
        $this->expirationTime = $expirationTime;
        return $this;
    }

    /**
     * Gets as statusMsgEnabled
     *
     * @return bool
     */
    public function getStatusMsgEnabled()
    {
        return $this->statusMsgEnabled;
    }

    /**
     * Sets a new statusMsgEnabled
     *
     * @param bool $statusMsgEnabled
     * @return self
     */
    public function setStatusMsgEnabled($statusMsgEnabled)
    {
        $this->statusMsgEnabled = $statusMsgEnabled;
        return $this;
    }


}

