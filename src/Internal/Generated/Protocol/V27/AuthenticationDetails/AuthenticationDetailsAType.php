<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\AuthenticationDetails;

/**
 * Class representing AuthenticationDetailsAType
 */
class AuthenticationDetailsAType
{

    /**
     * @var string $userId
     */
    private $userId = null;

    /**
     * @var string $sHA256Fingerprint
     */
    private $sHA256Fingerprint = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature $signature
     */
    private $signature = null;

    /**
     * Gets as userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets a new userId
     *
     * @param string $userId
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Gets as sHA256Fingerprint
     *
     * @return string
     */
    public function getSHA256Fingerprint()
    {
        return $this->sHA256Fingerprint;
    }

    /**
     * Sets a new sHA256Fingerprint
     *
     * @param string $sHA256Fingerprint
     * @return self
     */
    public function setSHA256Fingerprint($sHA256Fingerprint)
    {
        $this->sHA256Fingerprint = $sHA256Fingerprint;
        return $this;
    }

    /**
     * Gets as signature
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Sets a new signature
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature $signature
     * @return self
     */
    public function setSignature(?\Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Signature $signature = null)
    {
        $this->signature = $signature;
        return $this;
    }


}

