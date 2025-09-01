<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Domain;

use Psa\EpsBankTransfer\Domain\RefundResponse;
use PHPUnit\Framework\TestCase;
use Psa\EpsBankTransfer\Internal\Generated\Refund\EpsRefundResponse;

class RefundResponseTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $rr = new RefundResponse('000', 'No Errors');
        $this->assertSame('000', $rr->getStatusCode());
        $this->assertSame('No Errors', $rr->getErrorMessage());

        $rr2 = new RefundResponse('123', null);
        $this->assertSame('123', $rr2->getStatusCode());
        $this->assertNull($rr2->getErrorMessage());
    }

    public function testFromV26MapsFieldsWithErrorMessage(): void
    {
        $stub = new class {
            public function getStatusCode(): string { return '000'; }
            public function getErrorMsg(): ?string { return 'Keine Fehler - dt accepted'; }
        };

        // Use Reflection to satisfy the type-hint via covariance is not possible; instead, create a proxy using PHPUnit's createMock is complicated without the class.
        // RefundResponse::fromV26 only calls the two methods; we can bypass type check by using Closure binding is not possible.
        // To keep strict typing, we wrap a dynamic object into an anonymous class that extends the expected FQN at runtime via class_alias below.
        $this->ensureRefundEpsClassExists();
        $eps = new class extends EpsRefundResponse {
            public function __construct(){}
            public function getStatusCode(): string { return '000'; }
            public function getErrorMsg(): ?string { return 'Keine Fehler - dt accepted'; }
        };

        $rr = RefundResponse::fromV26($eps);
        $this->assertSame('000', $rr->getStatusCode());
        $this->assertSame('Keine Fehler - dt accepted', $rr->getErrorMessage());
    }

    public function testFromV26MapsNullErrorMessage(): void
    {
        $this->ensureRefundEpsClassExists();
        $eps = new class extends EpsRefundResponse {
            public function __construct(){}
            public function getStatusCode(): string { return '105'; }
            public function getErrorMsg(): ?string { return null; }
        };

        $rr = RefundResponse::fromV26($eps);
        $this->assertSame('105', $rr->getStatusCode());
        $this->assertNull($rr->getErrorMessage());
    }

    private function ensureRefundEpsClassExists(): void
    {
        if (!class_exists('Psa\\EpsBankTransfer\\Internal\\Generated\\Refund\\EpsRefundResponse')) {
            eval('namespace Psa\\EpsBankTransfer\\Internal\\Generated\\Refund; abstract class EpsRefundResponse { abstract public function getStatusCode(): string; abstract public function getErrorMsg(): ?string; }');
        }
    }
}
