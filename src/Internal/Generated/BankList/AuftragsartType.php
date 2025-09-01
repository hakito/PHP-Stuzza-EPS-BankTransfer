<?php

namespace Psa\EpsBankTransfer\Internal\Generated\BankList;

/**
 * Class representing AuftragsartType
 *
 *
 * XSD Type: auftragsart
 */
class AuftragsartType
{

    /**
     * @var string $__value
     */
    private $__value = null;

    /**
     * @var bool $terminueberweisung
     */
    private $terminueberweisung = null;

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

    /**
     * Gets as terminueberweisung
     *
     * @return bool
     */
    public function getTerminueberweisung()
    {
        return $this->terminueberweisung;
    }

    /**
     * Sets a new terminueberweisung
     *
     * @param bool $terminueberweisung
     * @return self
     */
    public function setTerminueberweisung($terminueberweisung)
    {
        $this->terminueberweisung = $terminueberweisung;
        return $this;
    }


}

