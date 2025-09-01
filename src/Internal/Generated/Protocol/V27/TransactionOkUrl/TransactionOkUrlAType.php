<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\TransactionOkUrl;

/**
 * Class representing TransactionOkUrlAType
 */
class TransactionOkUrlAType
{

    /**
     * @var string $__value
     */
    private $__value = null;

    /**
     * Browser window in which the OkUrl will be opened
     *
     * @var mixed $targetWindow
     */
    private $targetWindow = null;

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
     * Gets as targetWindow
     *
     * Browser window in which the OkUrl will be opened
     *
     * @return mixed
     */
    public function getTargetWindow()
    {
        return $this->targetWindow;
    }

    /**
     * Sets a new targetWindow
     *
     * Browser window in which the OkUrl will be opened
     *
     * @param mixed $targetWindow
     * @return self
     */
    public function setTargetWindow($targetWindow)
    {
        $this->targetWindow = $targetWindow;
        return $this;
    }


}

