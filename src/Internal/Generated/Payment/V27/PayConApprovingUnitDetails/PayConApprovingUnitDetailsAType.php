<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Payment\V27\PayConApprovingUnitDetails;

/**
 * Class representing PayConApprovingUnitDetailsAType
 */
class PayConApprovingUnitDetailsAType
{

    /**
     * @var string $approvingUnitBankIdentifier
     */
    private $approvingUnitBankIdentifier = null;

    /**
     * @var string $approvingUnitIdentifier
     */
    private $approvingUnitIdentifier = null;

    /**
     * Gets as approvingUnitBankIdentifier
     *
     * @return string
     */
    public function getApprovingUnitBankIdentifier()
    {
        return $this->approvingUnitBankIdentifier;
    }

    /**
     * Sets a new approvingUnitBankIdentifier
     *
     * @param string $approvingUnitBankIdentifier
     * @return self
     */
    public function setApprovingUnitBankIdentifier($approvingUnitBankIdentifier)
    {
        $this->approvingUnitBankIdentifier = $approvingUnitBankIdentifier;
        return $this;
    }

    /**
     * Gets as approvingUnitIdentifier
     *
     * @return string
     */
    public function getApprovingUnitIdentifier()
    {
        return $this->approvingUnitIdentifier;
    }

    /**
     * Sets a new approvingUnitIdentifier
     *
     * @param string $approvingUnitIdentifier
     * @return self
     */
    public function setApprovingUnitIdentifier($approvingUnitIdentifier)
    {
        $this->approvingUnitIdentifier = $approvingUnitIdentifier;
        return $this;
    }


}

