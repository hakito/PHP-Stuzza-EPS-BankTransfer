<?php

namespace Psa\EpsBankTransfer\Internal\Generated\BankList\EpsSOBankListProtocol;

/**
 * Class representing EpsSOBankListProtocolAType
 */
class EpsSOBankListProtocolAType
{

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\BankList\BankDataType[] $bank
     */
    private $bank = [
        
    ];

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\BankList\ErrorDataType $errorDetails
     */
    private $errorDetails = null;

    /**
     * Adds as bank
     *
     * @return self
     * @param \Psa\EpsBankTransfer\Internal\Generated\BankList\BankDataType $bank
     */
    public function addToBank(\Psa\EpsBankTransfer\Internal\Generated\BankList\BankDataType $bank)
    {
        $this->bank[] = $bank;
        return $this;
    }

    /**
     * isset bank
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBank($index)
    {
        return isset($this->bank[$index]);
    }

    /**
     * unset bank
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBank($index)
    {
        unset($this->bank[$index]);
    }

    /**
     * Gets as bank
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\BankList\BankDataType[]
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\BankList\BankDataType[] $bank
     * @return self
     */
    public function setBank(array $bank = null)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as errorDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\BankList\ErrorDataType
     */
    public function getErrorDetails()
    {
        return $this->errorDetails;
    }

    /**
     * Sets a new errorDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\BankList\ErrorDataType $errorDetails
     * @return self
     */
    public function setErrorDetails(?\Psa\EpsBankTransfer\Internal\Generated\BankList\ErrorDataType $errorDetails = null)
    {
        $this->errorDetails = $errorDetails;
        return $this;
    }


}

