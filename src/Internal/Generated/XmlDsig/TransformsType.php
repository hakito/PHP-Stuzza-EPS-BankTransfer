<?php

namespace Psa\EpsBankTransfer\Internal\Generated\XmlDsig;

/**
 * Class representing TransformsType
 *
 *
 * XSD Type: TransformsType
 */
class TransformsType
{

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Transform[] $transform
     */
    private $transform = [
        
    ];

    /**
     * Adds as transform
     *
     * @return self
     * @param \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Transform $transform
     */
    public function addToTransform(\Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Transform $transform)
    {
        $this->transform[] = $transform;
        return $this;
    }

    /**
     * isset transform
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTransform($index)
    {
        return isset($this->transform[$index]);
    }

    /**
     * unset transform
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTransform($index)
    {
        unset($this->transform[$index]);
    }

    /**
     * Gets as transform
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Transform[]
     */
    public function getTransform()
    {
        return $this->transform;
    }

    /**
     * Sets a new transform
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\Transform[] $transform
     * @return self
     */
    public function setTransform(array $transform)
    {
        $this->transform = $transform;
        return $this;
    }


}

