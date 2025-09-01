<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransferMsgDetails;

/**
 * Class representing TransferMsgDetailsAType
 */
class TransferMsgDetailsAType
{

    /**
     * @var string $confirmationUrl
     */
    private $confirmationUrl = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionOkUrl $transactionOkUrl
     */
    private $transactionOkUrl = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionNokUrl $transactionNokUrl
     */
    private $transactionNokUrl = null;

    /**
     * Gets as confirmationUrl
     *
     * @return string
     */
    public function getConfirmationUrl()
    {
        return $this->confirmationUrl;
    }

    /**
     * Sets a new confirmationUrl
     *
     * @param string $confirmationUrl
     * @return self
     */
    public function setConfirmationUrl($confirmationUrl)
    {
        $this->confirmationUrl = $confirmationUrl;
        return $this;
    }

    /**
     * Gets as transactionOkUrl
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionOkUrl
     */
    public function getTransactionOkUrl()
    {
        return $this->transactionOkUrl;
    }

    /**
     * Sets a new transactionOkUrl
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionOkUrl $transactionOkUrl
     * @return self
     */
    public function setTransactionOkUrl(\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionOkUrl $transactionOkUrl)
    {
        $this->transactionOkUrl = $transactionOkUrl;
        return $this;
    }

    /**
     * Gets as transactionNokUrl
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionNokUrl
     */
    public function getTransactionNokUrl()
    {
        return $this->transactionNokUrl;
    }

    /**
     * Sets a new transactionNokUrl
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionNokUrl $transactionNokUrl
     * @return self
     */
    public function setTransactionNokUrl(\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionNokUrl $transactionNokUrl)
    {
        $this->transactionNokUrl = $transactionNokUrl;
        return $this;
    }


}

