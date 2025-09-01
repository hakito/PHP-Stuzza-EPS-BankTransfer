<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Payment\V27\StatusReason;

/**
 * Class representing StatusReasonAType
 */
class StatusReasonAType
{

    /**
     * @var string $from
     */
    private $from = null;

    /**
     * @var string $reasonCode
     */
    private $reasonCode = null;

    /**
     * @var string $reasonMessage
     */
    private $reasonMessage = null;

    /**
     * Gets as from
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets a new from
     *
     * @param string $from
     * @return self
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Gets as reasonCode
     *
     * @return string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * Sets a new reasonCode
     *
     * @param string $reasonCode
     * @return self
     */
    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
        return $this;
    }

    /**
     * Gets as reasonMessage
     *
     * @return string
     */
    public function getReasonMessage()
    {
        return $this->reasonMessage;
    }

    /**
     * Sets a new reasonMessage
     *
     * @param string $reasonMessage
     * @return self
     */
    public function setReasonMessage($reasonMessage)
    {
        $this->reasonMessage = $reasonMessage;
        return $this;
    }


}

