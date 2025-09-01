<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Refund;

/**
 * Class representing MerchantIBAN
 *
 * The unique and unambiguous identification of the account for the account owner and the account servicer. This must be an IBAN (International Bank Account Number).
 */
class MerchantIBAN
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

