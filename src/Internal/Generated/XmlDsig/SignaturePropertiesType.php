<?php

namespace Psa\EpsBankTransfer\Internal\Generated\XmlDsig;

/**
 * Class representing SignaturePropertiesType
 *
 *
 * XSD Type: SignaturePropertiesType
 */
class SignaturePropertiesType
{

    /**
     * @var string $id
     */
    private $id = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\SignatureProperty[] $signatureProperty
     */
    private $signatureProperty = [
        
    ];

    /**
     * Gets as id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets a new id
     *
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Adds as signatureProperty
     *
     * @return self
     * @param \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\SignatureProperty $signatureProperty
     */
    public function addToSignatureProperty(\Psa\EpsBankTransfer\Internal\Generated\XmlDsig\SignatureProperty $signatureProperty)
    {
        $this->signatureProperty[] = $signatureProperty;
        return $this;
    }

    /**
     * isset signatureProperty
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSignatureProperty($index)
    {
        return isset($this->signatureProperty[$index]);
    }

    /**
     * unset signatureProperty
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSignatureProperty($index)
    {
        unset($this->signatureProperty[$index]);
    }

    /**
     * Gets as signatureProperty
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\SignatureProperty[]
     */
    public function getSignatureProperty()
    {
        return $this->signatureProperty;
    }

    /**
     * Sets a new signatureProperty
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\SignatureProperty[] $signatureProperty
     * @return self
     */
    public function setSignatureProperty(array $signatureProperty)
    {
        $this->signatureProperty = $signatureProperty;
        return $this;
    }


}

