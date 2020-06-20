<?php

namespace at\externet\eps_bank_transfer;

CONST XMLNS_eps = "http://www.stuzza.at/namespaces/eps/payment/2014/10";
CONST XMLNS_epi = "http://www.stuzza.at/namespaces/eps/epi/2013/02";
CONST XMLNS_epsp = "http://www.stuzza.at/namespaces/eps/protocol/2014/10";

function FormatMonetaryXsdDecimal($val)
{
    if (is_string($val))
    {
        if (preg_match('/^[0-9]+$/', $val) > 0)
            $val += 0;
    }

    if (!is_int($val))
    {
        throw new \InvalidArgumentException(sprintf("Int value expected but %s received", gettype($val)));
    }

    if (strlen($val) < 3)
    {
        $prefix = '0.';
        if (strlen($val) < 2)        
            $prefix = '0.0';
        
        if (strlen($val) < 1)
                return '0.00';            
        
        return $prefix . $val;
    }

    $intVal = substr($val, 0, -2);
    $centVal = substr($val, -2);
    return $intVal . '.' . $centVal;
}