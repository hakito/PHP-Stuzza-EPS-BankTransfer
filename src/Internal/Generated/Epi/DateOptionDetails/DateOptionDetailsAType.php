<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Epi\DateOptionDetails;

/**
 * Class representing DateOptionDetailsAType
 */
class DateOptionDetailsAType
{

    /**
     * Specifies whether the DateOption is a credit (CRD) or a debit (DBD) date
     *
     * @var string $dateSpecificationCode
     */
    private $dateSpecificationCode = null;

    /**
     * @var string $optionDate
     */
    private $optionDate = null;

    /**
     * @var string $optionTime
     */
    private $optionTime = null;

    /**
     * Gets as dateSpecificationCode
     *
     * Specifies whether the DateOption is a credit (CRD) or a debit (DBD) date
     *
     * @return string
     */
    public function getDateSpecificationCode()
    {
        return $this->dateSpecificationCode;
    }

    /**
     * Sets a new dateSpecificationCode
     *
     * Specifies whether the DateOption is a credit (CRD) or a debit (DBD) date
     *
     * @param string $dateSpecificationCode
     * @return self
     */
    public function setDateSpecificationCode($dateSpecificationCode)
    {
        $this->dateSpecificationCode = $dateSpecificationCode;
        return $this;
    }

    /**
     * Gets as optionDate
     *
     * @return string
     */
    public function getOptionDate()
    {
        return $this->optionDate;
    }

    /**
     * Sets a new optionDate
     *
     * @param string $optionDate
     * @return self
     */
    public function setOptionDate($optionDate)
    {
        $this->optionDate = $optionDate;
        return $this;
    }

    /**
     * Gets as optionTime
     *
     * @return string
     */
    public function getOptionTime()
    {
        return $this->optionTime;
    }

    /**
     * Sets a new optionTime
     *
     * @param string $optionTime
     * @return self
     */
    public function setOptionTime($optionTime)
    {
        $this->optionTime = $optionTime;
        return $this;
    }


}

