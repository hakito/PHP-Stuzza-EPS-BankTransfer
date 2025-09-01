<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\ShopResponseDetails;

/**
 * Class representing ShopResponseDetailsAType
 */
class ShopResponseDetailsAType
{

    /**
     * @var string $sessionId
     */
    private $sessionId = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Payment\V27\ShopConfirmationDetails $shopConfirmationDetails
     */
    private $shopConfirmationDetails = null;

    /**
     * @var string $errorMsg
     */
    private $errorMsg = null;

    /**
     * Gets as sessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Sets a new sessionId
     *
     * @param string $sessionId
     * @return self
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * Gets as shopConfirmationDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Payment\V27\ShopConfirmationDetails
     */
    public function getShopConfirmationDetails()
    {
        return $this->shopConfirmationDetails;
    }

    /**
     * Sets a new shopConfirmationDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Payment\V27\ShopConfirmationDetails $shopConfirmationDetails
     * @return self
     */
    public function setShopConfirmationDetails(?\Psa\EpsBankTransfer\Internal\Generated\Payment\V27\ShopConfirmationDetails $shopConfirmationDetails = null)
    {
        $this->shopConfirmationDetails = $shopConfirmationDetails;
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

