<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Refund\EpsRefundResponse;

/**
 * Class representing EpsRefundResponseAType
 */
class EpsRefundResponseAType
{

    /**
     * @var string $statusCode
     */
    private $statusCode = null;

    /**
     * @var string $errorMsg
     */
    private $errorMsg = null;

    /**
     * Gets as statusCode
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets a new statusCode
     *
     * @param string $statusCode
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Gets as errorMsg
     *
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * Sets a new errorMsg
     *
     * @param string $errorMsg
     * @return self
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
        return $this;
    }


}

