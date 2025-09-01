<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Domain;

use DateTimeInterface;
use Exception;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\EpsProtocolDetails;

class BankConfirmationDetails
{
    /** @var string */
    private $sessionId;
    /** @var string */
    private $remittanceIdentifier;
    /** @var string */
    private $approvingUnitBankIdentifier;
    /** @var DateTimeInterface */
    private $approvalTime;
    /** @var string */
    private $paymentReferenceIdentifier;
    /** @var string */
    private $statusCode;

    public function __construct(
        string $sessionId,
        string $remittanceIdentifier,
        string $approvingUnitBankIdentifier,
        DateTimeInterface $approvalTime,
        string $paymentReferenceIdentifier,
        string $statusCode
    ) {
        $this->sessionId = $sessionId;
        $this->remittanceIdentifier = $remittanceIdentifier;
        $this->approvingUnitBankIdentifier = $approvingUnitBankIdentifier;
        $this->approvalTime = $approvalTime;
        $this->paymentReferenceIdentifier = $paymentReferenceIdentifier;
        $this->statusCode = $statusCode;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getRemittanceIdentifier(): string
    {
        return $this->remittanceIdentifier;
    }

    public function getApprovingUnitBankIdentifier(): string
    {
        return $this->approvingUnitBankIdentifier;
    }

    public function getApprovalTime(): DateTimeInterface
    {
        return $this->approvalTime;
    }

    public function getPaymentReferenceIdentifier(): string
    {
        return $this->paymentReferenceIdentifier;
    }

    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * Factory method: create Domain object from generated XSD object
     * @throws Exception
     */
    public static function fromV26(EpsProtocolDetails $epsProtocolDetails): self
    {
        $bankConfirmation = $epsProtocolDetails->getBankConfirmationDetails();
        $payment = $bankConfirmation->getPaymentConfirmationDetails();

        // According to XSD: one of the choice elements is always present
        $remittanceId = $payment->getRemittanceIdentifier()
            ?: $payment->getUnstructuredRemittanceIdentifier();

        // According to XSD: one of BankIdentifier or Identifier is always present
        $approvingUnit =
            $payment->getPayConApprovingUnitDetails()->getApprovingUnitBankIdentifier()
                ?: $payment->getPayConApprovingUnitDetails()->getApprovingUnitIdentifier();

        return new self(
            $bankConfirmation->getSessionId(),
            $remittanceId,
            $approvingUnit,
            $payment->getPayConApprovalTime(),
            $payment->getPaymentReferenceIdentifier(),
            $payment->getStatusCode()
        );
    }
}
