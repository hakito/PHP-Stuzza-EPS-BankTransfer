<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankResponseDetails;

/**
 * Class representing BankResponseDetailsAType
 */
class BankResponseDetailsAType
{

    /**
     * @var string $clientRedirectUrl
     */
    private $clientRedirectUrl = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ErrorDetails $errorDetails
     */
    private $errorDetails = null;

    /**
     * @var string $transactionId
     */
    private $transactionId = null;

    /**
     * @var string $qRCodeUrl
     */
    private $qRCodeUrl = null;

    /**
     * Gets as clientRedirectUrl
     *
     * @return string
     */
    public function getClientRedirectUrl()
    {
        return $this->clientRedirectUrl;
    }

    /**
     * Sets a new clientRedirectUrl
     *
     * @param string $clientRedirectUrl
     * @return self
     */
    public function setClientRedirectUrl($clientRedirectUrl)
    {
        $this->clientRedirectUrl = $clientRedirectUrl;
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
    public function setErrorDetails(\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ErrorDetails $errorDetails)
    {
        $this->errorDetails = $errorDetails;
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
     * Gets as qRCodeUrl
     *
     * @return string
     */
    public function getQRCodeUrl()
    {
        return $this->qRCodeUrl;
    }

    /**
     * Sets a new qRCodeUrl
     *
     * @param string $qRCodeUrl
     * @return self
     */
    public function setQRCodeUrl($qRCodeUrl)
    {
        $this->qRCodeUrl = $qRCodeUrl;
        return $this;
    }


}

