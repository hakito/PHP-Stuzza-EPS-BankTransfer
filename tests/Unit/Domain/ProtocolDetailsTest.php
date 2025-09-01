<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Domain;

use Psa\EpsBankTransfer\Domain\ProtocolDetails;
use PHPUnit\Framework\TestCase;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\EpsProtocolDetails;

class ProtocolDetailsTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $pd = new ProtocolDetails('000', 'OK', 'https://bank.example/redirect', 'epsTID123');
        $this->assertSame('000', $pd->getErrorCode());
        $this->assertSame('OK', $pd->getErrorMessage());
        $this->assertSame('https://bank.example/redirect', $pd->getClientRedirectUrl());
        $this->assertSame('epsTID123', $pd->getTransactionId());
    }

    public function testFromV26MapsFieldsWithAndWithoutError(): void
    {
        $this->ensureV26Stubs();
        // Case with error details present
        $v26 = new class extends \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\EpsProtocolDetails {
            public function __construct() {}
            public function getBankResponseDetails() {
                return new class {
                    public function getErrorDetails() { return new class { public function getErrorCode(){return '105';} public function getErrorMsg(){return 'Invalid data';} }; }
                    public function getClientRedirectUrl(){ return 'https://example/redirect'; }
                    public function getTransactionId(){ return 'epsTID-26'; }
                };
            }
        };
        $pd = ProtocolDetails::fromV26($v26);
        $this->assertSame('105', $pd->getErrorCode());
        $this->assertSame('Invalid data', $pd->getErrorMessage());
        $this->assertSame('https://example/redirect', $pd->getClientRedirectUrl());
        $this->assertSame('epsTID-26', $pd->getTransactionId());

        // Case without error details
        $v26no = new class extends \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\EpsProtocolDetails {
            public function __construct() {}
            public function getBankResponseDetails(): object
            {
                return new class {
                    public function getErrorDetails() { return null; }
                    public function getClientRedirectUrl(){ return 'https://example/redirect2'; }
                    public function getTransactionId(){ return null; }
                };
            }
        };
        $pd2 = ProtocolDetails::fromV26($v26no);
        $this->assertNull($pd2->getErrorCode());
        $this->assertNull($pd2->getErrorMessage());
        $this->assertSame('https://example/redirect2', $pd2->getClientRedirectUrl());
        $this->assertNull($pd2->getTransactionId());
    }

    public function testFromV27MapsLikeV26(): void
    {
        $this->ensureV27Stubs();
        $v27 = new class extends EpsProtocolDetails {
            public function __construct() {}
            public function getBankResponseDetails() {
                return new class {
                    public function getErrorDetails() { return new class { public function getErrorCode(){return '000';} public function getErrorMsg(){return 'OK';} }; }
                    public function getClientRedirectUrl(){ return 'https://example/v27'; }
                    public function getTransactionId(){ return 'epsTID-27'; }
                };
            }
        };
        $pd = ProtocolDetails::fromV27($v27);
        $this->assertSame('000', $pd->getErrorCode());
        $this->assertSame('OK', $pd->getErrorMessage());
        $this->assertSame('https://example/v27', $pd->getClientRedirectUrl());
        $this->assertSame('epsTID-27', $pd->getTransactionId());
    }

    private function ensureV26Stubs(): void
    {
        if (!class_exists('Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V26\\EpsProtocolDetails')) {
            eval('namespace Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V26; abstract class EpsProtocolDetails { public function __construct() {} abstract public function getBankResponseDetails(); }');
        }
    }

    private function ensureV27Stubs(): void
    {
        if (!class_exists('Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V27\\EpsProtocolDetails')) {
            eval('namespace Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V27; abstract class EpsProtocolDetails { public function __construct() {} abstract public function getBankResponseDetails(); }');
        }
    }
}
