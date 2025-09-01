<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\TransactionDetailsResponse;

/**
 * Class representing TransactionDetailsResponseAType
 */
class TransactionDetailsResponseAType
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
     * @var \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature $signature
     */
    private $signature = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\ErrorDetails $errorDetails
     */
    private $errorDetails = null;

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
    public function setPaymentInitiatorDetails(?\Psa\EpsBankTransfer\Internal\Generated\Payment\V27\PaymentInitiatorDetails $paymentInitiatorDetails = null)
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
    public function setTransferMsgDetails(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\TransferMsgDetails $transferMsgDetails = null)
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

    /**
     * Gets as errorDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\ErrorDetails
     */
    public function getErrorDetails()
    {
        return $this->errorDetails;
    }

    /**
     * Sets a new errorDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\ErrorDetails $errorDetails
     * @return self
     */
    public function setErrorDetails(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\ErrorDetails $errorDetails = null)
    {
        $this->errorDetails = $errorDetails;
        return $this;
    }


}

