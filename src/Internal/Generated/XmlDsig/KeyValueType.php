<?php

namespace Psa\EpsBankTransfer\Internal\Generated\XmlDsig;

/**
 * Class representing KeyValueType
 *
 *
 * XSD Type: KeyValueType
 */
class KeyValueType
{

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\DSAKeyValue $dSAKeyValue
     */
    private $dSAKeyValue = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\RSAKeyValue $rSAKeyValue
     */
    private $rSAKeyValue = null;

    /**
     * Gets as dSAKeyValue
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\DSAKeyValue
     */
    public function getDSAKeyValue()
    {
        return $this->dSAKeyValue;
    }

    /**
     * Sets a new dSAKeyValue
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\DSAKeyValue $dSAKeyValue
     * @return self
     */
    public function setDSAKeyValue(?\Psa\EpsBankTransfer\Internal\Generated\XmlDsig\DSAKeyValue $dSAKeyValue = null)
    {
        $this->dSAKeyValue = $dSAKeyValue;
        return $this;
    }

    /**
     * Gets as rSAKeyValue
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\RSAKeyValue
     */
    public function getRSAKeyValue()
    {
        return $this->rSAKeyValue;
    }

    /**
     * Sets a new rSAKeyValue
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\XmlDsig\RSAKeyValue $rSAKeyValue
     * @return self
     */
    public function setRSAKeyValue(?\Psa\EpsBankTransfer\Internal\Generated\XmlDsig\RSAKeyValue $rSAKeyValue = null)
    {
        $this->rSAKeyValue = $rSAKeyValue;
        return $this;
    }


}

