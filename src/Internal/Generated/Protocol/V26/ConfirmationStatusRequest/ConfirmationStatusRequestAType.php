<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\ConfirmationStatusRequest;

/**
 * Class representing ConfirmationStatusRequestAType
 */
class ConfirmationStatusRequestAType
{

    /**
     * @var string $transactionId
     */
    private $transactionId = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\AuthenticationDetails $authenticationDetails
     */
    private $authenticationDetails = null;

    /**
     * Gets as transactionId
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Sets a new transactionId
     *
     * @param string $transactionId
     * @return self
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * Gets as authenticationDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\AuthenticationDetails
     */
    public function getAuthenticationDetails()
    {
        return $this->authenticationDetails;
    }

    /**
     * Sets a new authenticationDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\AuthenticationDetails $authenticationDetails
     * @return self
     */
    public function setAuthenticationDetails(\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\AuthenticationDetails $authenticationDetails)
    {
        $this->authenticationDetails = $authenticationDetails;
        return $this;
    }


}

