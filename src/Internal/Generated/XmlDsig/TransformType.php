<?php

namespace Psa\EpsBankTransfer\Internal\Generated\XmlDsig;

/**
 * Class representing TransformType
 *
 *
 * XSD Type: TransformType
 */
class TransformType
{

    /**
     * @var string $algorithm
     */
    private $algorithm = null;

    /**
     * @var string[] $xPath
     */
    private $xPath = [
        
    ];

    /**
     * Gets as algorithm
     *
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Sets a new algorithm
     *
     * @param string $algorithm
     * @return self
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    /**
     * Adds as xPath
     *
     * @return self
     * @param string $xPath
     */
    public function addToXPath($xPath)
    {
        $this->xPath[] = $xPath;
        return $this;
    }

    /**
     * isset xPath
     *
     * @param int|string $index
     * @return bool
     */
    public function issetXPath($index)
    {
        return isset($this->xPath[$index]);
    }

    /**
     * unset xPath
     *
     * @param int|string $index
     * @return void
     */
    public function unsetXPath($index)
    {
        unset($this->xPath[$index]);
    }

    /**
     * Gets as xPath
     *
     * @return string[]
     */
    public function getXPath()
    {
        return $this->xPath;
    }

    /**
     * Sets a new xPath
     *
     * @param string[] $xPath
     * @return self
     */
    public function setXPath(array $xPath = null)
    {
        $this->xPath = $xPath;
        return $this;
    }


}

