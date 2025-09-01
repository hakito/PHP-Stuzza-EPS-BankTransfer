<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Utilities;

use InvalidArgumentException;

final class MoneyFormatter
{
    public static function formatXsdDecimal($val): string
    {
        if (is_string($val) && ctype_digit($val)) {
            $val = (int)$val;
        }

        if (!is_int($val)) {
            throw new InvalidArgumentException(
                sprintf("Int value (cents) expected but %s received", gettype($val))
            );
        }

        return number_format($val / 100, 2, '.', '');
    }
}