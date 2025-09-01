<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\StatusMsg;

/**
 * Class representing StatusMsgAType
 */
class StatusMsgAType
{

    /**
     * @var string $transactionId
     */
    private $transactionId = null;

    /**
     * @var string $status
     */
    private $status = null;

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
     * Gets as status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * @param string $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }


}

