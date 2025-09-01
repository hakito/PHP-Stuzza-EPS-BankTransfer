<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Payment\V26\PaymentInitiatorDetails;

/**
 * Class representing PaymentInitiatorDetailsAType
 */
class PaymentInitiatorDetailsAType
{

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Epi\EpiDetails $epiDetails
     */
    private $epiDetails = null;

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\AustrianRules\AustrianRulesDetails $austrianRulesDetails
     */
    private $austrianRulesDetails = null;

    /**
     * Gets as epiDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Epi\EpiDetails
     */
    public function getEpiDetails()
    {
        return $this->epiDetails;
    }

    /**
     * Sets a new epiDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Epi\EpiDetails $epiDetails
     * @return self
     */
    public function setEpiDetails(\Psa\EpsBankTransfer\Internal\Generated\Epi\EpiDetails $epiDetails)
    {
        $this->epiDetails = $epiDetails;
        return $this;
    }

    /**
     * Gets as austrianRulesDetails
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\AustrianRules\AustrianRulesDetails
     */
    public function getAustrianRulesDetails()
    {
        return $this->austrianRulesDetails;
    }

    /**
     * Sets a new austrianRulesDetails
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\AustrianRules\AustrianRulesDetails $austrianRulesDetails
     * @return self
     */
    public function setAustrianRulesDetails(?\Psa\EpsBankTransfer\Internal\Generated\AustrianRules\AustrianRulesDetails $austrianRulesDetails = null)
    {
        $this->austrianRulesDetails = $austrianRulesDetails;
        return $this;
    }


}

