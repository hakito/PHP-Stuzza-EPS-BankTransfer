<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Epi;

/**
 * Class representing RemittanceIdentifier
 *
 * max35, A string of characters, to be forwarded with the payment throughout the payment chain in order to identify and reconcile the credit transfer upon receipt by the ultimate beneficiary
 */
class RemittanceIdentifier
{

    /**
     * @var string $__value
     */
    private $__value = null;

    /**
     * Construct
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param string $value
     * @return string
     */
    public function value()
    {
        if ($args = func_get_args()) {
            $this->__value = $args[0];
        }
        return $this->__value;
    }

    /**
     * Gets a string value
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->__value);
    }


}

