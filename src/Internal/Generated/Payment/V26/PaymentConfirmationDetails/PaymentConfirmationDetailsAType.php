<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentConfirmationDetails;

/**
 * Class representing PaymentConfirmationDetailsAType
 */
class PaymentConfirmationDetailsAType
{

    /**
     * @var string $remittanceIdentifier
     */
    private $remittanceIdentifier = null;

    /**
     * @var string $unstructuredRemittanceIdentifier
     */
    private $unstructuredRemittanceIdentifier = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentInitiatorDetails $paymentInitiatorDetails
     */
    private $paymentInitiatorDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PayConApprovingUnitDetails $payConApprovingUnitDetails
     */
    private $payConApprovingUnitDetails = null;

    /**
     * @var \DateTime $payConApprovalTime
     */
    private $payConApprovalTime = null;

    /**
     * @var string $paymentReferenceIdentifier
     */
    private $paymentReferenceIdentifier = null;

    /**
     * @var string $statusCode
     */
    private $statusCode = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature $signature
     */
    private $signature = null;

    /**
     * Gets as remittanceIdentifier
     *
     * @return string
     */
    public function getRemittanceIdentifier()
    {
        return $this->remittanceIdentifier;
    }

    /**
     * Sets a new remittanceIdentifier
     *
     * @param string $remittanceIdentifier
     * @return self
     */
    public function setRemittanceIdentifier($remittanceIdentifier)
    {
        $this->remittanceIdentifier = $remittanceIdentifier;
        return $this;
    }

    /**
     * Gets as unstructuredRemittanceIdentifier
     *
     * @return string
     */
    public function getUnstructuredRemittanceIdentifier()
    {
        return $this->unstructuredRemittanceIdentifier;
    }

    /**
     * Sets a new unstructuredRemittanceIdentifier
     *
     * @param string $unstructuredRemittanceIdentifier
     * @return self
     */
    public function setUnstructuredRemittanceIdentifier($unstructuredRemittanceIdentifier)
    {
        $this->unstructuredRemittanceIdentifier = $unstructuredRemittanceIdentifier;
        return $this;
    }

    /**
     * Gets as paymentInitiatorDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentInitiatorDetails
     */
    public function getPaymentInitiatorDetails()
    {
        return $this->paymentInitiatorDetails;
    }

    /**
     * Sets a new paymentInitiatorDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentInitiatorDetails $paymentInitiatorDetails
     * @return self
     */
    public function setPaymentInitiatorDetails(?\Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentInitiatorDetails $paymentInitiatorDetails = null)
    {
        $this->paymentInitiatorDetails = $paymentInitiatorDetails;
        return $this;
    }

    /**
     * Gets as payConApprovingUnitDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PayConApprovingUnitDetails
     */
    public function getPayConApprovingUnitDetails()
    {
        return $this->payConApprovingUnitDetails;
    }

    /**
     * Sets a new payConApprovingUnitDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PayConApprovingUnitDetails $payConApprovingUnitDetails
     * @return self
     */
    public function setPayConApprovingUnitDetails(\Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PayConApprovingUnitDetails $payConApprovingUnitDetails)
    {
        $this->payConApprovingUnitDetails = $payConApprovingUnitDetails;
        return $this;
    }

    /**
     * Gets as payConApprovalTime
     *
     * @return \DateTime
     */
    public function getPayConApprovalTime()
    {
        return $this->payConApprovalTime;
    }

    /**
     * Sets a new payConApprovalTime
     *
     * @param \DateTime $payConApprovalTime
     * @return self
     */
    public function setPayConApprovalTime(\DateTime $payConApprovalTime)
    {
        $this->payConApprovalTime = $payConApprovalTime;
        return $this;
    }

    /**
     * Gets as paymentReferenceIdentifier
     *
     * @return string
     */
    public function getPaymentReferenceIdentifier()
    {
        return $this->paymentReferenceIdentifier;
    }

    /**
     * Sets a new paymentReferenceIdentifier
     *
     * @param string $paymentReferenceIdentifier
     * @return self
     */
    public function setPaymentReferenceIdentifier($paymentReferenceIdentifier)
    {
        $this->paymentReferenceIdentifier = $paymentReferenceIdentifier;
        return $this;
    }

    /**
     * Gets as statusCode
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets a new statusCode
     *
     * @param string $statusCode
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
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

