<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\EpsProtocolDetails;

/**
 * Class representing EpsProtocolDetailsAType
 */
class EpsProtocolDetailsAType
{

    /**
     * @var string $sessionLanguage
     */
    private $sessionLanguage = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransferInitiatorDetails $transferInitiatorDetails
     */
    private $transferInitiatorDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankResponseDetails $bankResponseDetails
     */
    private $bankResponseDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\VitalityCheckDetails $vitalityCheckDetails
     */
    private $vitalityCheckDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankConfirmationDetails $bankConfirmationDetails
     */
    private $bankConfirmationDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ShopResponseDetails $shopResponseDetails
     */
    private $shopResponseDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionDetailsRequest $transactionDetailsRequest
     */
    private $transactionDetailsRequest = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionDetailsResponse $transactionDetailsResponse
     */
    private $transactionDetailsResponse = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusRequest $confirmationStatusRequest
     */
    private $confirmationStatusRequest = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusResponse $confirmationStatusResponse
     */
    private $confirmationStatusResponse = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\StatusMsg $statusMsg
     */
    private $statusMsg = null;

    /**
     * Gets as sessionLanguage
     *
     * @return string
     */
    public function getSessionLanguage()
    {
        return $this->sessionLanguage;
    }

    /**
     * Sets a new sessionLanguage
     *
     * @param string $sessionLanguage
     * @return self
     */
    public function setSessionLanguage($sessionLanguage)
    {
        $this->sessionLanguage = $sessionLanguage;
        return $this;
    }

    /**
     * Gets as transferInitiatorDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransferInitiatorDetails
     */
    public function getTransferInitiatorDetails()
    {
        return $this->transferInitiatorDetails;
    }

    /**
     * Sets a new transferInitiatorDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransferInitiatorDetails $transferInitiatorDetails
     * @return self
     */
    public function setTransferInitiatorDetails(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransferInitiatorDetails $transferInitiatorDetails = null)
    {
        $this->transferInitiatorDetails = $transferInitiatorDetails;
        return $this;
    }

    /**
     * Gets as bankResponseDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankResponseDetails
     */
    public function getBankResponseDetails()
    {
        return $this->bankResponseDetails;
    }

    /**
     * Sets a new bankResponseDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankResponseDetails $bankResponseDetails
     * @return self
     */
    public function setBankResponseDetails(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankResponseDetails $bankResponseDetails = null)
    {
        $this->bankResponseDetails = $bankResponseDetails;
        return $this;
    }

    /**
     * Gets as vitalityCheckDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\VitalityCheckDetails
     */
    public function getVitalityCheckDetails()
    {
        return $this->vitalityCheckDetails;
    }

    /**
     * Sets a new vitalityCheckDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\VitalityCheckDetails $vitalityCheckDetails
     * @return self
     */
    public function setVitalityCheckDetails(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\VitalityCheckDetails $vitalityCheckDetails = null)
    {
        $this->vitalityCheckDetails = $vitalityCheckDetails;
        return $this;
    }

    /**
     * Gets as bankConfirmationDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankConfirmationDetails
     */
    public function getBankConfirmationDetails()
    {
        return $this->bankConfirmationDetails;
    }

    /**
     * Sets a new bankConfirmationDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankConfirmationDetails $bankConfirmationDetails
     * @return self
     */
    public function setBankConfirmationDetails(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\BankConfirmationDetails $bankConfirmationDetails = null)
    {
        $this->bankConfirmationDetails = $bankConfirmationDetails;
        return $this;
    }

    /**
     * Gets as shopResponseDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ShopResponseDetails
     */
    public function getShopResponseDetails()
    {
        return $this->shopResponseDetails;
    }

    /**
     * Sets a new shopResponseDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ShopResponseDetails $shopResponseDetails
     * @return self
     */
    public function setShopResponseDetails(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ShopResponseDetails $shopResponseDetails = null)
    {
        $this->shopResponseDetails = $shopResponseDetails;
        return $this;
    }

    /**
     * Gets as transactionDetailsRequest
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionDetailsRequest
     */
    public function getTransactionDetailsRequest()
    {
        return $this->transactionDetailsRequest;
    }

    /**
     * Sets a new transactionDetailsRequest
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionDetailsRequest $transactionDetailsRequest
     * @return self
     */
    public function setTransactionDetailsRequest(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionDetailsRequest $transactionDetailsRequest = null)
    {
        $this->transactionDetailsRequest = $transactionDetailsRequest;
        return $this;
    }

    /**
     * Gets as transactionDetailsResponse
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionDetailsResponse
     */
    public function getTransactionDetailsResponse()
    {
        return $this->transactionDetailsResponse;
    }

    /**
     * Sets a new transactionDetailsResponse
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionDetailsResponse $transactionDetailsResponse
     * @return self
     */
    public function setTransactionDetailsResponse(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\TransactionDetailsResponse $transactionDetailsResponse = null)
    {
        $this->transactionDetailsResponse = $transactionDetailsResponse;
        return $this;
    }

    /**
     * Gets as confirmationStatusRequest
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusRequest
     */
    public function getConfirmationStatusRequest()
    {
        return $this->confirmationStatusRequest;
    }

    /**
     * Sets a new confirmationStatusRequest
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusRequest $confirmationStatusRequest
     * @return self
     */
    public function setConfirmationStatusRequest(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusRequest $confirmationStatusRequest = null)
    {
        $this->confirmationStatusRequest = $confirmationStatusRequest;
        return $this;
    }

    /**
     * Gets as confirmationStatusResponse
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusResponse
     */
    public function getConfirmationStatusResponse()
    {
        return $this->confirmationStatusResponse;
    }

    /**
     * Sets a new confirmationStatusResponse
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusResponse $confirmationStatusResponse
     * @return self
     */
    public function setConfirmationStatusResponse(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusResponse $confirmationStatusResponse = null)
    {
        $this->confirmationStatusResponse = $confirmationStatusResponse;
        return $this;
    }

    /**
     * Gets as statusMsg
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\StatusMsg
     */
    public function getStatusMsg()
    {
        return $this->statusMsg;
    }

    /**
     * Sets a new statusMsg
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\StatusMsg $statusMsg
     * @return self
     */
    public function setStatusMsg(?\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\StatusMsg $statusMsg = null)
    {
        $this->statusMsg = $statusMsg;
        return $this;
    }


}

