<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Domain;

class VitalityCheckDetails
{
    /**
     * @var string|null
     */
    private $remittanceIdentifier;

    /**
     * @var string|null
     */
    private $unstructuredRemittanceIdentifier;

    /**
     * @param string|null $remittanceIdentifier
     * @param string|null $unstructuredRemittanceIdentifier
     */
    public function __construct(?string $remittanceIdentifier = null, ?string $unstructuredRemittanceIdentifier = null)
    {
        if ($remittanceIdentifier === null && $unstructuredRemittanceIdentifier === null) {
            throw new \InvalidArgumentException(
                'VitalityCheckDetails must have either remittanceIdentifier or unstructuredRemittanceIdentifier'
            );
        }
        if ($remittanceIdentifier !== null && $unstructuredRemittanceIdentifier !== null) {
            throw new \InvalidArgumentException(
                'VitalityCheckDetails cannot have both remittanceIdentifier and unstructuredRemittanceIdentifier'
            );
        }

        $this->remittanceIdentifier = $remittanceIdentifier;
        $this->unstructuredRemittanceIdentifier = $unstructuredRemittanceIdentifier;
    }

    /**
     * @return string|null
     */
    public function getRemittanceIdentifier(): ?string
    {
        return $this->remittanceIdentifier;
    }

    /**
     * @return string|null
     */
    public function getUnstructuredRemittanceIdentifier(): ?string
    {
        return $this->unstructuredRemittanceIdentifier;
    }

    /**
     * @return bool
     */
    public function isStructured(): bool
    {
        return $this->remittanceIdentifier !== null;
    }

    /**
     * @return bool
     */
    public function isUnstructured(): bool
    {
        return $this->unstructuredRemittanceIdentifier !== null;
    }

    /**
     * @param \Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\VitalityCheckDetails $vitalityCheckDetails
     * @return self
     */
    public static function fromV26(\Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\VitalityCheckDetails $vitalityCheckDetails): self
    {
        return new self(
            $vitalityCheckDetails->getRemittanceIdentifier(),
            $vitalityCheckDetails->getUnstructuredRemittanceIdentifier()
        );
    }
}
