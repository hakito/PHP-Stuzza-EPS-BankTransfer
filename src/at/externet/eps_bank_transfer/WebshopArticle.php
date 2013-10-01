<?php

namespace at\externet\eps_bank_transfer;
require_once "functions.php";

class WebshopArticle
{

    /** @var item name */
    public $Name;
    
    /** @var number of items */
    public $Count;
    
    /** @var string representation of price */
    public $Price;

    /**
     * 
     * @param string $name item name
     * @param int $count number of items
     * @param int $price price in cents
     */
    public function __construct($name, $count, $price)
    {
        $this->Name = $name;
        $this->Count = $count;
        $this->SetPrice($price);
    }

    /**
     * 
     * @param int $value in cents
     */
    public function SetPrice($value)
    {
        $this->Price = FormatMonetaryXsdDecimal($value);
    }

}
?>
