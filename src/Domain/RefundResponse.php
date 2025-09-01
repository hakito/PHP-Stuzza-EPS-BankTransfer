<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Domain;

use Psa\EpsBankTransfer\Internal\Generated\Refund\EpsRefundResponse;

/**
 * Result of an EPS refund request mapped into a domain object.
 */
class RefundResponse
{
    private $statusCode;
    private $errorMessage;

    /**
     * Create a refund response.
     *
     * @param string $statusCode Status code ("000" means success at SO level).
     * @param string|null $errorMessage Optional error message.
     */
    public function __construct(string $statusCode, ?string $errorMessage)
    {
        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Map v2.6 generated refund response to domain model.
     */
    public static function fromV26(EpsRefundResponse $response): self
    {
        return new self(
            $response->getStatusCode(),
            $response->getErrorMsg() ?? null
        );
    }

    /** Status code of the refund request as returned by the SO. */
    public function getStatusCode(): string { return $this->statusCode; }
    /** Optional error message returned by the SO. */
    public function getErrorMessage(): ?string { return $this->errorMessage; }
}
