<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Epi\PartyDetails;

/**
 * Class representing PartyDetailsAType
 */
class PartyDetailsAType
{

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Epi\BfiPartyDetails $bfiPartyDetails
     */
    private $bfiPartyDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Epi\BeneficiaryPartyDetails $beneficiaryPartyDetails
     */
    private $beneficiaryPartyDetails = null;

    /**
     * Gets as bfiPartyDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Epi\BfiPartyDetails
     */
    public function getBfiPartyDetails()
    {
        return $this->bfiPartyDetails;
    }

    /**
     * Sets a new bfiPartyDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Epi\BfiPartyDetails $bfiPartyDetails
     * @return self
     */
    public function setBfiPartyDetails(\Psa\EpsBankTransfer\Internal\Generated\Epi\BfiPartyDetails $bfiPartyDetails)
    {
        $this->bfiPartyDetails = $bfiPartyDetails;
        return $this;
    }

    /**
     * Gets as beneficiaryPartyDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Epi\BeneficiaryPartyDetails
     */
    public function getBeneficiaryPartyDetails()
    {
        return $this->beneficiaryPartyDetails;
    }

    /**
     * Sets a new beneficiaryPartyDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Epi\BeneficiaryPartyDetails $beneficiaryPartyDetails
     * @return self
     */
    public function setBeneficiaryPartyDetails(\Psa\EpsBankTransfer\Internal\Generated\Epi\BeneficiaryPartyDetails $beneficiaryPartyDetails)
    {
        $this->beneficiaryPartyDetails = $beneficiaryPartyDetails;
        return $this;
    }


}

