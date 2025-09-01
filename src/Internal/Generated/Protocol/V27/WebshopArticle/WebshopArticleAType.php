<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle;

/**
 * Class representing WebshopArticleAType
 */
class WebshopArticleAType
{

    /**
     * @var string $articleName
     */
    private $articleName = null;

    /**
     * @var string $articleCount
     */
    private $articleCount = null;

    /**
     * @var float $articlePrice
     */
    private $articlePrice = null;

    /**
     * Gets as articleName
     *
     * @return string
     */
    public function getArticleName()
    {
        return $this->articleName;
    }

    /**
     * Sets a new articleName
     *
     * @param string $articleName
     * @return self
     */
    public function setArticleName($articleName)
    {
        $this->articleName = $articleName;
        return $this;
    }

    /**
     * Gets as articleCount
     *
     * @return string
     */
    public function getArticleCount()
    {
        return $this->articleCount;
    }

    /**
     * Sets a new articleCount
     *
     * @param string $articleCount
     * @return self
     */
    public function setArticleCount($articleCount)
    {
        $this->articleCount = $articleCount;
        return $this;
    }

    /**
     * Gets as articlePrice
     *
     * @return float
     */
    public function getArticlePrice()
    {
        return $this->articlePrice;
    }

    /**
     * Sets a new articlePrice
     *
     * @param float $articlePrice
     * @return self
     */
    public function setArticlePrice($articlePrice)
    {
        $this->articlePrice = $articlePrice;
        return $this;
    }


}

