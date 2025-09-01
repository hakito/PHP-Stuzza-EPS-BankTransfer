<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\TransferInitiatorDetails;

/**
 * Class representing TransferInitiatorDetailsAType
 */
class TransferInitiatorDetailsAType
{

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Payment\V27\PaymentInitiatorDetails $paymentInitiatorDetails
     */
    private $paymentInitiatorDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\TransferMsgDetails $transferMsgDetails
     */
    private $transferMsgDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle[] $webshopDetails
     */
    private $webshopDetails = null;

    /**
     * @var string $transactionId
     */
    private $transactionId = null;

    /**
     * @var string $qRCodeUrl
     */
    private $qRCodeUrl = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\AuthenticationDetails $authenticationDetails
     */
    private $authenticationDetails = null;

    /**
     * Gets as paymentInitiatorDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Payment\V27\PaymentInitiatorDetails
     */
    public function getPaymentInitiatorDetails()
    {
        return $this->paymentInitiatorDetails;
    }

    /**
     * Sets a new paymentInitiatorDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Payment\V27\PaymentInitiatorDetails $paymentInitiatorDetails
     * @return self
     */
    public function setPaymentInitiatorDetails(\Psa\EpsBankTransfer\Internal\Generated\Payment\V27\PaymentInitiatorDetails $paymentInitiatorDetails)
    {
        $this->paymentInitiatorDetails = $paymentInitiatorDetails;
        return $this;
    }

    /**
     * Gets as transferMsgDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\TransferMsgDetails
     */
    public function getTransferMsgDetails()
    {
        return $this->transferMsgDetails;
    }

    /**
     * Sets a new transferMsgDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\TransferMsgDetails $transferMsgDetails
     * @return self
     */
    public function setTransferMsgDetails(\Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\TransferMsgDetails $transferMsgDetails)
    {
        $this->transferMsgDetails = $transferMsgDetails;
        return $this;
    }

    /**
     * Adds as webshopArticle
     *
     * @return self
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle $webshopArticle
     */
    public function addToWebshopDetails(\Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle $webshopArticle)
    {
        $this->webshopDetails[] = $webshopArticle;
        return $this;
    }

    /**
     * isset webshopDetails
     *
     * @param int|string $index
     * @return bool
     */
    public function issetWebshopDetails($index)
    {
        return isset($this->webshopDetails[$index]);
    }

    /**
     * unset webshopDetails
     *
     * @param int|string $index
     * @return void
     */
    public function unsetWebshopDetails($index)
    {
        unset($this->webshopDetails[$index]);
    }

    /**
     * Gets as webshopDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle[]
     */
    public function getWebshopDetails()
    {
        return $this->webshopDetails;
    }

    /**
     * Sets a new webshopDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle[] $webshopDetails
     * @return self
     */
    public function setWebshopDetails(array $webshopDetails = null)
    {
        $this->webshopDetails = $webshopDetails;
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

    /**
     * Gets as authenticationDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\AuthenticationDetails
     */
    public function getAuthenticationDetails()
    {
        return $this->authenticationDetails;
    }

    /**
     * Sets a new authenticationDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\AuthenticationDetails $authenticationDetails
     * @return self
     */
    public function setAuthenticationDetails(\Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\AuthenticationDetails $authenticationDetails)
    {
        $this->authenticationDetails = $authenticationDetails;
        return $this;
    }


}

