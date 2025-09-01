<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Api;

use Psa\EpsBankTransfer\Domain\BankList;
use Psa\EpsBankTransfer\Domain\ProtocolDetails;
use Psa\EpsBankTransfer\Domain\RefundResponse;
use Psa\EpsBankTransfer\Exceptions\BankListException;
use Psa\EpsBankTransfer\Exceptions\CallbackResponseException;
use Psa\EpsBankTransfer\Exceptions\InvalidCallbackException;
use Psa\EpsBankTransfer\Exceptions\XmlValidationException;
use Psa\EpsBankTransfer\Internal\V26\SoV26Communicator;
use Psa\EpsBankTransfer\Internal\V27\SoV27Communicator;
use Psa\EpsBankTransfer\Requests\RefundRequest;
use Psa\EpsBankTransfer\Requests\TransferInitiatorDetails;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Default implementation of the public API to communicate with the EPS Scheme Operator (SO).
 *
 * This class delegates version-specific logic to internal communicators (v2.6, v2.7),
 * providing a stable facade for application code.
 */
class SoCommunicator implements SoCommunicatorInterface
{
    public const LIVE_MODE_URL = 'https://routing.eps.or.at/appl/epsSO';
    public const TEST_MODE_URL = 'https://routing-test.eps.or.at/appl/epsSO';

    /** @var ClientInterface */
    private $httpClient;
    /** @var RequestFactoryInterface */
    private $requestFactory;
    /** @var StreamFactoryInterface */
    private $streamFactory;
    /** @var LoggerInterface|null */
    private $logger;
    /** @var string */
    private $baseUrl;

    /** @var SoV26Communicator|null */
    private $v26 = null;

    /** @var SoV27Communicator|null */
    private $v27 = null;

    /**
     * Create a new SO communicator facade.
     *
     * @param ClientInterface $httpClient PSR-18 HTTP client used for requests.
     * @param RequestFactoryInterface $requestFactory PSR-17 request factory.
     * @param StreamFactoryInterface $streamFactory PSR-17 stream factory.
     * @param string $baseUrl Base URL for the SO (LIVE by default). You may pass TEST_MODE_URL.
     * @param LoggerInterface|null $logger Optional PSR-3 logger for diagnostics.
     */
    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        string $baseUrl = self::LIVE_MODE_URL,
        LoggerInterface $logger = null
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->baseUrl = $baseUrl;
        $this->logger = $logger;
    }

    /**
     * Lazily create and return the version 2.6 communicator.
     */
    private function getV26(): ?SoV26Communicator
    {
        if ($this->v26 === null) {
            $this->v26 = new SoV26Communicator(
                $this->httpClient,
                $this->requestFactory,
                $this->streamFactory,
                $this->baseUrl,
                $this->logger
            );
        }
        return $this->v26;
    }

    /**
     * Lazily create and return the version 2.7 communicator.
     */
    private function getV27(): ?SoV27Communicator
    {
        if ($this->v27 === null) {
            $this->v27 = new SoV27Communicator(
                $this->httpClient,
                $this->requestFactory,
                $this->streamFactory,
                $this->baseUrl,
                $this->logger
            );
        }
        return $this->v27;
    }

    /**
     * Fetch the bank list from the SO and map it to a domain object.
     *
     * @param string $version Interface version ("2.6" or "2.7"). Bank list supported for 2.6.
     * @param string|null $targetUrl Optional custom target URL instead of the default.
     * @return BankList List of supported banks.
     * @throws BankListException When the SO returns an error payload.
     * @throws XmlValidationException When response validation fails.
     */
    public function getBanks(string $version = '2.6', ?string $targetUrl = null): BankList
    {
        $this->assertValidVersion($version);

        if ($version === '2.6') {
            $raw = $this->getV26()->getBanks($targetUrl);

            if ($raw->getErrorDetails() !== null) {
                throw new BankListException('Error: ' . $raw->getErrorDetails()->getErrorMsg());
            }

            return BankList::fromV26($raw);
        }

        $this->getV27()->getBanks($targetUrl); // throws exception at this time
    }

    /**
     * Send a refund request to the SO and map the response to a domain object.
     *
     * @param RefundRequest $refundRequest Refund request details.
     * @param string $version Interface version ("2.6" or "2.7"). Refunds supported for 2.6.
     * @param string|null $targetUrl Optional custom target URL instead of the default.
     * @return RefundResponse Result details mapped to a domain object.
     * @throws XmlValidationException When response validation fails.
     */
    public function sendRefundRequest(RefundRequest $refundRequest, string $version = '2.6', ?string $targetUrl = null): RefundResponse
    {
        $this->assertValidVersion($version);

        if ($version === '2.6') {
            $raw = $this->getV26()->sendRefundRequest($refundRequest, $targetUrl);
            return RefundResponse::fromV26($raw);
        }

        $this->getV27()->sendRefundRequest($refundRequest, $targetUrl); // throws exception at this time
    }


    /**
     * Send transfer initiator details to the SO and map the response to a domain object.
     *
     * @param TransferInitiatorDetails $transferInitiatorDetails Payment initiation details.
     * @param string $version Interface version ("2.6" or "2.7").
     * @param string|null $targetUrl Optional custom target URL instead of the default.
     * @return ProtocolDetails Protocol details mapped to a domain object.
     * @throws XmlValidationException When response validation fails.
     */
    public function sendTransferInitiatorDetails(
        TransferInitiatorDetails $transferInitiatorDetails,
        string $version = '2.6',
        ?string $targetUrl = null
    ): ProtocolDetails
    {
        $this->assertValidVersion($version);

        if ($version === '2.6') {
            $raw = $this->getV26()->sendTransferInitiatorDetails($transferInitiatorDetails, $targetUrl);
            return ProtocolDetails::fromV26($raw);
        }

        $this->getV27()->sendTransferInitiatorDetails($transferInitiatorDetails, $targetUrl);
    }

    /**
     * Handle confirmation and vitality-check callbacks from the SO.
     *
     * The provided callbacks should return true when processing succeeds.
     *
     * @param callable|null $confirmationCallback Callback invoked on payment confirmations.
     * @param callable|null $vitalityCheckCallback Optional callback invoked on vitality checks.
     * @param string $rawPostStream Input stream for raw POST data (e.g. "php://input").
     * @param string $outputStream Output stream for the SO response (e.g. "php://output").
     * @param string $version Interface version ("2.6" or "2.7").
     * @throws InvalidCallbackException When the provided callbacks are invalid.
     * @throws XmlValidationException When request/response validation fails.
     * @throws CallbackResponseException When callback handling fails or returns an invalid response.
     */
    public function handleConfirmationUrl(
        $confirmationCallback = null,
        $vitalityCheckCallback = null,
        $rawPostStream = 'php://input',
        $outputStream = 'php://output',
        string $version = '2.6'
    ): void
    {
        $this->assertValidVersion($version);

        if ($version === '2.6') {
            $this->getV26()->handleConfirmationUrl(
                $confirmationCallback,
                $vitalityCheckCallback,
                $rawPostStream,
                $outputStream
            );
            return;
        }

        $this->getV27()->handleConfirmationUrl(
            $confirmationCallback,
            $vitalityCheckCallback,
            $rawPostStream,
            $outputStream
        );
    }

    /**
     * Set the base URL for subsequent requests to the SO.
     *
     * @param string $baseUrl Base URL used for building request endpoints.
     */
    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Ensure the provided interface version is supported.
     *
     * @param string $version Version string (e.g., "2.6" or "2.7").
     * @throws \InvalidArgumentException When the version is not supported.
     */
    private function assertValidVersion(string $version): void
    {
        $allowed = ['2.6', '2.7'];
        if (!in_array($version, $allowed, true)) {
            throw new \InvalidArgumentException(
                sprintf('Unsupported version "%s". Allowed versions are: %s', $version, implode(', ', $allowed))
            );
        }
    }
}
