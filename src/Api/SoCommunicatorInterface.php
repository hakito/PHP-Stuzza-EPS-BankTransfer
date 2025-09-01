<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Api;

use InvalidArgumentException;
use LogicException;
use Psa\EpsBankTransfer\Domain\BankList;
use Psa\EpsBankTransfer\Domain\ProtocolDetails;
use Psa\EpsBankTransfer\Domain\RefundResponse;
use Psa\EpsBankTransfer\Exceptions\BankListException;
use Psa\EpsBankTransfer\Exceptions\CallbackResponseException;
use Psa\EpsBankTransfer\Exceptions\InvalidCallbackException;
use Psa\EpsBankTransfer\Exceptions\XmlValidationException;
use Psa\EpsBankTransfer\Requests\RefundRequest;
use Psa\EpsBankTransfer\Requests\TransferInitiatorDetails;

/**
 * Public API interface for interacting with the EPS Scheme Operator (SO).
 *
 * This interface abstracts high-level operations:
 * - Fetching the bank list supported by EPS
 * - Initiating a payment (transfer initiator)
 * - Requesting a refund
 * - Handling callback requests (confirmation and vitality check)
 */
interface SoCommunicatorInterface
{
    /**
     * Fetches the current bank list from the Scheme Operator (SO).
     *
     * The bank list is currently available for interface version 2.6 only.
     *
     * @param string $version Interface version ("2.6" or "2.7"). Bank list is 2.6.
     * @param string|null $targetUrl Optional custom target URL instead of the default.
     * @return BankList List of supported banks.
     * @throws InvalidArgumentException When an unsupported version is provided.
     * @throws BankListException When SO responds with an error payload.
     * @throws XmlValidationException When response XML fails XSD validation.
     * @throws LogicException For version 2.7 (not implemented yet).
     */
    public function getBanks(string $version = '2.6', ?string $targetUrl = null): BankList;

    /**
     * Sends a Transfer Initiator request to the Scheme Operator (SO).
     *
     * @param TransferInitiatorDetails $transferInitiatorDetails Details of the payment initiation.
     * @param string $version Version of the SO interface ("2.6" or "2.7").
     * @param string|null $targetUrl Optional custom target URL instead of the default.
     * @return ProtocolDetails Result of the request mapped into a domain object.
     * @throws InvalidArgumentException When an unsupported version is provided.
     * @throws XmlValidationException When request/response XML validation fails.
     * @throws LogicException For version 2.7 (not implemented yet).
     */
    public function sendTransferInitiatorDetails(
        TransferInitiatorDetails $transferInitiatorDetails,
        string                   $version = '2.6',
        ?string                  $targetUrl = null
    ): ProtocolDetails;

    /**
     * Sends a refund request to the Scheme Operator (SO).
     *
     * Refund is currently available for interface version 2.6 only.
     *
     * @param RefundRequest $refundRequest Refund request details.
     * @param string $version Version of the SO interface ("2.6" or "2.7").
     * @param string|null $targetUrl Optional custom target URL instead of the default.
     * @return RefundResponse Result of the request mapped into a domain object.
     * @throws InvalidArgumentException When an unsupported version is provided.
     * @throws XmlValidationException When request/response XML validation fails.
     * @throws LogicException For version 2.7 (not implemented yet).
     */
    public function sendRefundRequest(
        RefundRequest $refundRequest,
        string        $version = '2.6',
        ?string       $targetUrl = null
    ): RefundResponse;

    /**
     * Processes callback requests from the SO (Bank Confirmation / VitalityCheck).
     *
     * The confirmation callback should return true when the confirmation has been processed successfully.
     * The vitality check callback (optional) should return true for a valid vitality check.
     *
     * @param callable|null $confirmationCallback Callback invoked for payment confirmations. Must return true.
     * @param callable|null $vitalityCheckCallback Optional callback for vitality checks. Must return true.
     * @param string $rawPostStream Input stream to read the raw POST data (e.g. "php://input").
     * @param string $outputStream Output stream to write the response (e.g. "php://output").
     * @param string $version Interface version ("2.6" or "2.7").
     * @throws InvalidCallbackException When callbacks are missing or not callable.
     * @throws XmlValidationException When request/response XML validation fails.
     * @throws CallbackResponseException When a callback does not return true.
     * @throws LogicException For version 2.7 (not implemented yet).
     */
    public function handleConfirmationUrl(
        $confirmationCallback = null,
        $vitalityCheckCallback = null,
        string $rawPostStream = 'php://input',
        string $outputStream = 'php://output',
        string $version = '2.6'
    ): void;

    /**
     * Configuration: Set the base URL of the Scheme Operator (e.g., LIVE or TEST).
     *
     * @param string $baseUrl Base URL used for outgoing requests.
     */
    public function setBaseUrl(string $baseUrl): void;
}
