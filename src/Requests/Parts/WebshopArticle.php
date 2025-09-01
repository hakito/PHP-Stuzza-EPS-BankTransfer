<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Requests\Parts;

use Psa\EpsBankTransfer\Utilities\MoneyFormatter;

class WebshopArticle
{

    /** @var string name */
    public $name;
    
    /** @var number of items */
    public $count;
    
    /** @var string representation of price */
    public $price;

    /**
     * 
     * @param string $name item name
     * @param int $count number of items
     * @param int $price price in cents
     */
    public function __construct(string $name, int $count, int $price)
    {
        $this->name = $name;
        $this->count = $count;
        $this->setPrice($price);
    }

    /**
     * 
     * @param int $value in cents
     */
    public function setPrice(int $value)
    {
        $this->price = MoneyFormatter::formatXsdDecimal($value);
    }
}

