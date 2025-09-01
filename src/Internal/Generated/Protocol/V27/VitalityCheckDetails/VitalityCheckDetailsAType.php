<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\VitalityCheckDetails;

/**
 * Class representing VitalityCheckDetailsAType
 */
class VitalityCheckDetailsAType
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
     * @var string $orderingCustomerIdentifier
     */
    private $orderingCustomerIdentifier = null;

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
     * Gets as orderingCustomerIdentifier
     *
     * @return string
     */
    public function getOrderingCustomerIdentifier()
    {
        return $this->orderingCustomerIdentifier;
    }

    /**
     * Sets a new orderingCustomerIdentifier
     *
     * @param string $orderingCustomerIdentifier
     * @return self
     */
    public function setOrderingCustomerIdentifier($orderingCustomerIdentifier)
    {
        $this->orderingCustomerIdentifier = $orderingCustomerIdentifier;
        return $this;
    }


}

