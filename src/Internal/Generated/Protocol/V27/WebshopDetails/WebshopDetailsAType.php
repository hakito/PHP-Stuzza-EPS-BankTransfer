<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopDetails;

/**
 * Class representing WebshopDetailsAType
 */
class WebshopDetailsAType
{

    /**
     * @var \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle[] $webshopArticle
     */
    private $webshopArticle = [
        
    ];

    /**
     * Adds as webshopArticle
     *
     * @return self
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle $webshopArticle
     */
    public function addToWebshopArticle(\Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle $webshopArticle)
    {
        $this->webshopArticle[] = $webshopArticle;
        return $this;
    }

    /**
     * isset webshopArticle
     *
     * @param int|string $index
     * @return bool
     */
    public function issetWebshopArticle($index)
    {
        return isset($this->webshopArticle[$index]);
    }

    /**
     * unset webshopArticle
     *
     * @param int|string $index
     * @return void
     */
    public function unsetWebshopArticle($index)
    {
        unset($this->webshopArticle[$index]);
    }

    /**
     * Gets as webshopArticle
     *
     * @return \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle[]
     */
    public function getWebshopArticle()
    {
        return $this->webshopArticle;
    }

    /**
     * Sets a new webshopArticle
     *
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\WebshopArticle[] $webshopArticle
     * @return self
     */
    public function setWebshopArticle(array $webshopArticle)
    {
        $this->webshopArticle = $webshopArticle;
        return $this;
    }


}

