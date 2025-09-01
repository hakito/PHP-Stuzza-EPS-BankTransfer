<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Epi\PaymentInstructionDetails;

/**
 * Class representing PaymentInstructionDetailsAType
 */
class PaymentInstructionDetailsAType
{

    /**
     * @var string $paymentInstructionIdentifier
     */
    private $paymentInstructionIdentifier = null;

    /**
     * @var string $transactionTypeCode
     */
    private $transactionTypeCode = null;

    /**
     * @var string $instructionCode
     */
    private $instructionCode = null;

    /**
     * @var string $remittanceIdentifier
     */
    private $remittanceIdentifier = null;

    /**
     * @var string $unstructuredRemittanceIdentifier
     */
    private $unstructuredRemittanceIdentifier = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Epi\InstructedAmount $instructedAmount
     */
    private $instructedAmount = null;

    /**
     * @var string $chargeCode
     */
    private $chargeCode = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Epi\DateOptionDetails $dateOptionDetails
     */
    private $dateOptionDetails = null;

    /**
     * Gets as paymentInstructionIdentifier
     *
     * @return string
     */
    public function getPaymentInstructionIdentifier()
    {
        return $this->paymentInstructionIdentifier;
    }

    /**
     * Sets a new paymentInstructionIdentifier
     *
     * @param string $paymentInstructionIdentifier
     * @return self
     */
    public function setPaymentInstructionIdentifier($paymentInstructionIdentifier)
    {
        $this->paymentInstructionIdentifier = $paymentInstructionIdentifier;
        return $this;
    }

    /**
     * Gets as transactionTypeCode
     *
     * @return string
     */
    public function getTransactionTypeCode()
    {
        return $this->transactionTypeCode;
    }

    /**
     * Sets a new transactionTypeCode
     *
     * @param string $transactionTypeCode
     * @return self
     */
    public function setTransactionTypeCode($transactionTypeCode)
    {
        $this->transactionTypeCode = $transactionTypeCode;
        return $this;
    }

    /**
     * Gets as instructionCode
     *
     * @return string
     */
    public function getInstructionCode()
    {
        return $this->instructionCode;
    }

    /**
     * Sets a new instructionCode
     *
     * @param string $instructionCode
     * @return self
     */
    public function setInstructionCode($instructionCode)
    {
        $this->instructionCode = $instructionCode;
        return $this;
    }

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
     * Gets as instructedAmount
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Epi\InstructedAmount
     */
    public function getInstructedAmount()
    {
        return $this->instructedAmount;
    }

    /**
     * Sets a new instructedAmount
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Epi\InstructedAmount $instructedAmount
     * @return self
     */
    public function setInstructedAmount(\Psa\EpsBankTransfer\Internal\Generated\Epi\InstructedAmount $instructedAmount)
    {
        $this->instructedAmount = $instructedAmount;
        return $this;
    }

    /**
     * Gets as chargeCode
     *
     * @return string
     */
    public function getChargeCode()
    {
        return $this->chargeCode;
    }

    /**
     * Sets a new chargeCode
     *
     * @param string $chargeCode
     * @return self
     */
    public function setChargeCode($chargeCode)
    {
        $this->chargeCode = $chargeCode;
        return $this;
    }

    /**
     * Gets as dateOptionDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Epi\DateOptionDetails
     */
    public function getDateOptionDetails()
    {
        return $this->dateOptionDetails;
    }

    /**
     * Sets a new dateOptionDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Epi\DateOptionDetails $dateOptionDetails
     * @return self
     */
    public function setDateOptionDetails(?\Psa\EpsBankTransfer\Internal\Generated\Epi\DateOptionDetails $dateOptionDetails = null)
    {
        $this->dateOptionDetails = $dateOptionDetails;
        return $this;
    }


}

