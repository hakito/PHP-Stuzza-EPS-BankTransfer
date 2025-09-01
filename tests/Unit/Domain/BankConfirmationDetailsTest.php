<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Domain;

use DateTimeImmutable;
use Psa\EpsBankTransfer\Domain\BankConfirmationDetails;
use PHPUnit\Framework\TestCase;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\EpsProtocolDetails;

class BankConfirmationDetailsTest extends TestCase
{
    public function testFromV26MapsAllFieldsStructured(): void
    {
        $this->ensureV26ProtocolStub();
        $dt = new DateTimeImmutable('2025-01-01T12:00:00+00:00');

        $eps = new class($dt) extends EpsProtocolDetails {
            private $dt;
            public function __construct($dt){ $this->dt = $dt; }
            public function getBankConfirmationDetails() {
                $dt = $this->dt;
                return new class($dt) {
                    private $dt;
                    public function __construct($dt){ $this->dt = $dt; }
                    public function getSessionId(){ return 'sess-123'; }
                    public function getPaymentConfirmationDetails(){
                        $dt = $this->dt;
                        return new class($dt) {
                            private $dt;
                            public function __construct($dt){ $this->dt = $dt; }
                            public function getRemittanceIdentifier(){ return 'ORDER-1'; }
                            public function getUnstructuredRemittanceIdentifier(){ return null; }
                            public function getPayConApprovingUnitDetails(){
                                return new class {
                                    public function getApprovingUnitBankIdentifier(){ return 'GAWIATW1XXX'; }
                                    public function getApprovingUnitIdentifier(){ return null; }
                                };
                            }
                            public function getPayConApprovalTime(){ return $this->dt; }
                            public function getPaymentReferenceIdentifier(){ return 'epsM7DPP3R12'; }
                            public function getStatusCode(){ return 'OK'; }
                        };
                    }
                };
            }
        };

        $b = BankConfirmationDetails::fromV26($eps);
        $this->assertSame('sess-123', $b->getSessionId());
        $this->assertSame('ORDER-1', $b->getRemittanceIdentifier());
        $this->assertSame('GAWIATW1XXX', $b->getApprovingUnitBankIdentifier());
        $this->assertSame($dt, $b->getApprovalTime());
        $this->assertSame('epsM7DPP3R12', $b->getPaymentReferenceIdentifier());
        $this->assertSame('OK', $b->getStatusCode());
    }

    public function testFromV26MapsChoiceFieldsFallbacks(): void
    {
        $this->ensureV26ProtocolStub();
        $dt = new DateTimeImmutable('2025-01-01T12:05:00+00:00');

        $eps = new class($dt) extends EpsProtocolDetails {
            private $dt;
            public function __construct($dt){ $this->dt = $dt; }
            public function getBankConfirmationDetails() {
                $dt = $this->dt;
                return new class($dt) {
                    private $dt;
                    public function __construct($dt){ $this->dt = $dt; }
                    public function getSessionId(){ return 'sess-999'; }
                    public function getPaymentConfirmationDetails(){
                        $dt = $this->dt;
                        return new class($dt) {
                            private $dt;
                            public function __construct($dt){ $this->dt = $dt; }
                            public function getRemittanceIdentifier(){ return null; }
                            public function getUnstructuredRemittanceIdentifier(){ return 'Purpose text'; }
                            public function getPayConApprovingUnitDetails(){
                                return new class {
                                    public function getApprovingUnitBankIdentifier(){ return null; }
                                    public function getApprovingUnitIdentifier(){ return 'UNIT-42'; }
                                };
                            }
                            public function getPayConApprovalTime(){ return $this->dt; }
                            public function getPaymentReferenceIdentifier(){ return 'epsXYZ'; }
                            public function getStatusCode(){ return 'NOK'; }
                        };
                    }
                };
            }
        };

        $b = BankConfirmationDetails::fromV26($eps);
        $this->assertSame('sess-999', $b->getSessionId());
        $this->assertSame('Purpose text', $b->getRemittanceIdentifier());
        $this->assertSame('UNIT-42', $b->getApprovingUnitBankIdentifier());
        $this->assertSame($dt, $b->getApprovalTime());
        $this->assertSame('epsXYZ', $b->getPaymentReferenceIdentifier());
        $this->assertSame('NOK', $b->getStatusCode());
    }

    private function ensureV26ProtocolStub(): void
    {
        if (!class_exists('Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V26\\EpsProtocolDetails')) {
            eval('namespace Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V26; abstract class EpsProtocolDetails { public function __construct() {} abstract public function getBankConfirmationDetails(); }');
        }
    }
}
