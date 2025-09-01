<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ErrorDetails;

/**
 * Class representing ErrorDetailsAType
 */
class ErrorDetailsAType
{

    /**
     * @var string $errorCode
     */
    private $errorCode = null;

    /**
     * @var string $errorMsg
     */
    private $errorMsg = null;

    /**
     * Gets as errorCode
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Sets a new errorCode
     *
     * @param string $errorCode
     * @return self
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
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

