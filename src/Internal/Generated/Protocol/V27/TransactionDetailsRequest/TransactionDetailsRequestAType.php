<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\TransactionDetailsRequest;

/**
 * Class representing TransactionDetailsRequestAType
 */
class TransactionDetailsRequestAType
{

    /**
     * @var string $transactionId
     */
    private $transactionId = null;

    /**
     * @var string $bankId
     */
    private $bankId = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature $signature
     */
    private $signature = null;

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
     * Gets as bankId
     *
     * @return string
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * Sets a new bankId
     *
     * @param string $bankId
     * @return self
     */
    public function setBankId($bankId)
    {
        $this->bankId = $bankId;
        return $this;
    }

    /**
     * Gets as signature
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Sets a new signature
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature $signature
     * @return self
     */
    public function setSignature(?\Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature $signature = null)
    {
        $this->signature = $signature;
        return $this;
    }


}

