<?php

namespace at\externet\eps_bank_transfer;

class XmlValidationException extends ShopResponseException
{
    public function GetShopResponseErrorMessage()
    {
        return 'Error occured during XML validation';
    }
}