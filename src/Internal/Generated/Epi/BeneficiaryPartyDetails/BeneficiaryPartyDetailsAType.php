<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Epi\BeneficiaryPartyDetails;

/**
 * Class representing BeneficiaryPartyDetailsAType
 */
class BeneficiaryPartyDetailsAType
{

    /**
     * @var string $beneficiaryNameAddressText
     */
    private $beneficiaryNameAddressText = null;

    /**
     * @var string $beneficiaryBeiIdentifier
     */
    private $beneficiaryBeiIdentifier = null;

    /**
     * @var string $beneficiaryAccountIdentifier
     */
    private $beneficiaryAccountIdentifier = null;

    /**
     * Gets as beneficiaryNameAddressText
     *
     * @return string
     */
    public function getBeneficiaryNameAddressText()
    {
        return $this->beneficiaryNameAddressText;
    }

    /**
     * Sets a new beneficiaryNameAddressText
     *
     * @param string $beneficiaryNameAddressText
     * @return self
     */
    public function setBeneficiaryNameAddressText($beneficiaryNameAddressText)
    {
        $this->beneficiaryNameAddressText = $beneficiaryNameAddressText;
        return $this;
    }

    /**
     * Gets as beneficiaryBeiIdentifier
     *
     * @return string
     */
    public function getBeneficiaryBeiIdentifier()
    {
        return $this->beneficiaryBeiIdentifier;
    }

    /**
     * Sets a new beneficiaryBeiIdentifier
     *
     * @param string $beneficiaryBeiIdentifier
     * @return self
     */
    public function setBeneficiaryBeiIdentifier($beneficiaryBeiIdentifier)
    {
        $this->beneficiaryBeiIdentifier = $beneficiaryBeiIdentifier;
        return $this;
    }

    /**
     * Gets as beneficiaryAccountIdentifier
     *
     * @return string
     */
    public function getBeneficiaryAccountIdentifier()
    {
        return $this->beneficiaryAccountIdentifier;
    }

    /**
     * Sets a new beneficiaryAccountIdentifier
     *
     * @param string $beneficiaryAccountIdentifier
     * @return self
     */
    public function setBeneficiaryAccountIdentifier($beneficiaryAccountIdentifier)
    {
        $this->beneficiaryAccountIdentifier = $beneficiaryAccountIdentifier;
        return $this;
    }


}

