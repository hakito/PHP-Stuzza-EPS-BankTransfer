<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Epi;

/**
 * Class representing InstructionCode
 *
 * Further stipulates instruction related to the processing of the payment instruction. This can relate to a level of service between the financial institution and the customer, or give instruction for the next parties in the payment chain (e.g. intermediaries)
 */
class InstructionCode
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

