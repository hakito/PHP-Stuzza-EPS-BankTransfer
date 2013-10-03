<?php

namespace at\externet\eps_bank_transfer;

class ShopResponseException extends \Exception
{
    public function GetShopResponseErrorMessage()
    {
        return $this->getMessage();
    }
}