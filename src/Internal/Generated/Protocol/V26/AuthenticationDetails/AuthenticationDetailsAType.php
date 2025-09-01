<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\AuthenticationDetails;

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
     * @var string $mD5Fingerprint
     */
    private $mD5Fingerprint = null;

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
     * Gets as mD5Fingerprint
     *
     * @return string
     */
    public function getMD5Fingerprint()
    {
        return $this->mD5Fingerprint;
    }

    /**
     * Sets a new mD5Fingerprint
     *
     * @param string $mD5Fingerprint
     * @return self
     */
    public function setMD5Fingerprint($mD5Fingerprint)
    {
        $this->mD5Fingerprint = $mD5Fingerprint;
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

