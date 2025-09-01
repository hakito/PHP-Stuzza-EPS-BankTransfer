<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Domain;

use Psa\EpsBankTransfer\Domain\VitalityCheckDetails;
use PHPUnit\Framework\TestCase;

class VitalityCheckDetailsTest extends TestCase
{
    public function testConstructorRequiresExactlyOneIdentifier(): void
    {
        // both null -> throws
        $this->expectException(\InvalidArgumentException::class);
        new VitalityCheckDetails(null, null);
    }

    public function testConstructorThrowsWhenBothProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new VitalityCheckDetails('RI', 'URI');
    }

    public function testStructuredVariant(): void
    {
        $v = new VitalityCheckDetails('RI123', null);
        $this->assertSame('RI123', $v->getRemittanceIdentifier());
        $this->assertNull($v->getUnstructuredRemittanceIdentifier());
        $this->assertTrue($v->isStructured());
        $this->assertFalse($v->isUnstructured());
    }

    public function testUnstructuredVariant(): void
    {
        $v = new VitalityCheckDetails(null, 'Text purpose');
        $this->assertNull($v->getRemittanceIdentifier());
        $this->assertSame('Text purpose', $v->getUnstructuredRemittanceIdentifier());
        $this->assertFalse($v->isStructured());
        $this->assertTrue($v->isUnstructured());
    }

    public function testFromV26Structured(): void
    {
        $this->ensureProtocolClassExists();
        $proto = new class extends \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\VitalityCheckDetails {
            public function __construct(){}
            public function getRemittanceIdentifier(): ?string { return 'ABC123'; }
            public function getUnstructuredRemittanceIdentifier(): ?string { return null; }
        };

        $v = VitalityCheckDetails::fromV26($proto);
        $this->assertSame('ABC123', $v->getRemittanceIdentifier());
        $this->assertNull($v->getUnstructuredRemittanceIdentifier());
        $this->assertTrue($v->isStructured());
        $this->assertFalse($v->isUnstructured());
    }

    public function testFromV26Unstructured(): void
    {
        $this->ensureProtocolClassExists();
        $proto = new class extends \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\VitalityCheckDetails {
            public function __construct(){}
            public function getRemittanceIdentifier(): ?string { return null; }
            public function getUnstructuredRemittanceIdentifier(): ?string { return 'Purpose text'; }
        };

        $v = VitalityCheckDetails::fromV26($proto);
        $this->assertNull($v->getRemittanceIdentifier());
        $this->assertSame('Purpose text', $v->getUnstructuredRemittanceIdentifier());
        $this->assertFalse($v->isStructured());
        $this->assertTrue($v->isUnstructured());
    }

    private function ensureProtocolClassExists(): void
    {
        if (!class_exists('Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V26\\VitalityCheckDetails')) {
            eval('namespace Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V26; abstract class VitalityCheckDetails { abstract public function getRemittanceIdentifier(): ?string; abstract public function getUnstructuredRemittanceIdentifier(): ?string; }');
        }
    }
}
