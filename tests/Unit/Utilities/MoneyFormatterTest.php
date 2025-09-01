<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Utilities;

use Psa\EpsBankTransfer\Utilities\MoneyFormatter;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MoneyFormatterTest extends TestCase
{
    /**
     * @dataProvider provideValidAmounts
     */
    public function testFormatXsdDecimalValid($input, string $expected): void
    {
        $this->assertSame($expected, MoneyFormatter::formatXsdDecimal($input));
    }

    public static function provideValidAmounts(): array
    {
        return [
            'zero'               => [0, '0.00'],
            '1 cent'             => [1, '0.01'],
            '12 cents'           => [12, '0.12'],
            '1 euro'             => [100, '1.00'],
            '10 euro'            => [1000, '10.00'],
            '123 euro 45 cent'   => [12345, '123.45'],
            'string int'         => ['12345', '123.45'],
        ];
    }

    /**
     * @dataProvider provideInvalidAmounts
     */
    public function testFormatXsdDecimalInvalid($input): void
    {
        $this->expectException(InvalidArgumentException::class);
        MoneyFormatter::formatXsdDecimal($input);
    }

    public static function provideInvalidAmounts(): array
    {
        return [
            'float'      => [123.45],
            'float str'  => ['123.45'],
            'alpha str'  => ['abc'],
            'null'       => [null],
            'array'      => [[100]],
        ];
    }
}
