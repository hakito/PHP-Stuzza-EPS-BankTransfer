<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Domain;

use Psa\EpsBankTransfer\Domain\Bank;
use PHPUnit\Framework\TestCase;

class BankTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $b = new Bank('GAWIATW1XXX', 'Hypo Tirol', 'https://eps.example/bank', 'AT');
        $this->assertSame('GAWIATW1XXX', $b->getBic());
        $this->assertSame('Hypo Tirol', $b->getName());
        $this->assertSame('https://eps.example/bank', $b->getUrl());
        $this->assertSame('AT', $b->getCountryCode());

        $b2 = new Bank('BAWATW1XXX', 'BAWAG');
        $this->assertSame('BAWATW1XXX', $b2->getBic());
        $this->assertSame('BAWAG', $b2->getName());
        $this->assertNull($b2->getUrl());
        $this->assertSame('', $b2->getCountryCode());
    }
}
