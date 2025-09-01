<?php

namespace Psa\EpsBankTransfer\Internal\Generated\BankList;

/**
 * Class representing BankDataType
 *
 *
 * XSD Type: bankData
 */
class BankDataType
{

    /**
     * @var string $bic
     */
    private $bic = null;

    /**
     * @var string $bezeichnung
     */
    private $bezeichnung = null;

    /**
     * @var string $land
     */
    private $land = null;

    /**
     * @var string $epsUrl
     */
    private $epsUrl = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\BankList\AuftragsartType[] $zahlungsweiseNat
     */
    private $zahlungsweiseNat = [
        
    ];

    /**
     * @var string $zahlungsweiseInt
     */
    private $zahlungsweiseInt = null;

    /**
     * Gets as bic
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * @param string $bic
     * @return self
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as bezeichnung
     *
     * @return string
     */
    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     * Sets a new bezeichnung
     *
     * @param string $bezeichnung
     * @return self
     */
    public function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
        return $this;
    }

    /**
     * Gets as land
     *
     * @return string
     */
    public function getLand()
    {
        return $this->land;
    }

    /**
     * Sets a new land
     *
     * @param string $land
     * @return self
     */
    public function setLand($land)
    {
        $this->land = $land;
        return $this;
    }

    /**
     * Gets as epsUrl
     *
     * @return string
     */
    public function getEpsUrl()
    {
        return $this->epsUrl;
    }

    /**
     * Sets a new epsUrl
     *
     * @param string $epsUrl
     * @return self
     */
    public function setEpsUrl($epsUrl)
    {
        $this->epsUrl = $epsUrl;
        return $this;
    }

    /**
     * Adds as zahlungsweiseNat
     *
     * @return self
     * @param \Psa\EpsBankTransfer\Internal\Generated\BankList\AuftragsartType $zahlungsweiseNat
     */
    public function addToZahlungsweiseNat(\Psa\EpsBankTransfer\Internal\Generated\BankList\AuftragsartType $zahlungsweiseNat)
    {
        $this->zahlungsweiseNat[] = $zahlungsweiseNat;
        return $this;
    }

    /**
     * isset zahlungsweiseNat
     *
     * @param int|string $index
     * @return bool
     */
    public function issetZahlungsweiseNat($index)
    {
        return isset($this->zahlungsweiseNat[$index]);
    }

    /**
     * unset zahlungsweiseNat
     *
     * @param int|string $index
     * @return void
     */
    public function unsetZahlungsweiseNat($index)
    {
        unset($this->zahlungsweiseNat[$index]);
    }

    /**
     * Gets as zahlungsweiseNat
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\BankList\AuftragsartType[]
     */
    public function getZahlungsweiseNat()
    {
        return $this->zahlungsweiseNat;
    }

    /**
     * Sets a new zahlungsweiseNat
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\BankList\AuftragsartType[] $zahlungsweiseNat
     * @return self
     */
    public function setZahlungsweiseNat(array $zahlungsweiseNat)
    {
        $this->zahlungsweiseNat = $zahlungsweiseNat;
        return $this;
    }

    /**
     * Gets as zahlungsweiseInt
     *
     * @return string
     */
    public function getZahlungsweiseInt()
    {
        return $this->zahlungsweiseInt;
    }

    /**
     * Sets a new zahlungsweiseInt
     *
     * @param string $zahlungsweiseInt
     * @return self
     */
    public function setZahlungsweiseInt($zahlungsweiseInt)
    {
        $this->zahlungsweiseInt = $zahlungsweiseInt;
        return $this;
    }


}

