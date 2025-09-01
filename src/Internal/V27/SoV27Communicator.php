<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Internal\V27;

use Psa\EpsBankTransfer\Internal\Generated\BankList\EpsSOBankListProtocol;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V27\EpsProtocolDetails;
use Psa\EpsBankTransfer\Internal\Generated\Refund\EpsRefundResponse;
use Psa\EpsBankTransfer\Internal\SoCommunicatorCore;
use Psa\EpsBankTransfer\Requests\RefundRequest;
use Psa\EpsBankTransfer\Requests\TransferInitiatorDetails;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Internal communicator for EPS interface version 2.7.
 *
 * Note: Functionality is intentionally not implemented yet because the official
 * XSD 2.7 is pending. All public methods throw LogicException to make the
 * limitation explicit to integrators while keeping the public API forward-compatible.
 */
class SoV27Communicator
{
    /** @var SoCommunicatorCore */
    private $core;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * Create the v2.7 communicator with shared HTTP/serialization core.
     *
     * @param ClientInterface $httpClient PSR-18 HTTP client.
     * @param RequestFactoryInterface $requestFactory PSR-17 request factory.
     * @param StreamFactoryInterface $streamFactory PSR-17 stream factory.
     * @param string $baseUrl Base URL used to build endpoint URLs.
     * @param LoggerInterface|null $logger Optional PSR-3 logger.
     */
    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        string $baseUrl,
        LoggerInterface $logger = null
    ) {
        $this->core = new SoCommunicatorCore(
            $httpClient,
            $requestFactory,
            $streamFactory,
            $baseUrl,
            $logger
        );

        $this->serializer = $this->core->getSerializer();
    }

    /**
     * Send transfer initiator details using the (future) v2.7 schema.
     *
     * Currently not implemented until official XSD 2.7 is published.
     *
     * @param TransferInitiatorDetails $transferInitiatorDetails Payment initiation details.
     * @param string|null $targetUrl Optional custom target URL instead of the default.
     * @return EpsProtocolDetails
     * @throws \LogicException Always thrown until v2.7 support is implemented.
     */
    public function sendTransferInitiatorDetails(
        TransferInitiatorDetails $transferInitiatorDetails,
        ?string                  $targetUrl = null
    ): EpsProtocolDetails {
        throw new \LogicException('Not implemented yet - waiting for XSD 2.7');
    }

    /**
     * Handle confirmation and vitality-check callbacks for v2.7.
     *
     * Not implemented until XSD 2.7 is available.
     *
     * @param callable|null $confirmationCallback Callback for payment confirmations. Must return true.
     * @param callable|null $vitalityCheckCallback Callback for vitality checks. Must return true.
     * @param string $rawPostStream Input stream holding the raw POST body.
     * @param string $outputStream Output stream to write the response to the SO.
     * @throws \LogicException Always thrown until v2.7 support is implemented.
     */
    public function handleConfirmationUrl(
        $confirmationCallback = null,
        $vitalityCheckCallback = null,
        string $rawPostStream = 'php://input',
        string $outputStream = 'php://output'
    ): void {
        throw new \LogicException('Not implemented yet - waiting for XSD 2.7');
    }

    /**
     * Fetch the bank list using the v2.7 interface.
     *
     * Not implemented until XSD 2.7 is available.
     *
     * @param string|null $targetUrl Optional custom target URL instead of the default.
     * @return EpsSOBankListProtocol
     * @throws \LogicException Always thrown until v2.7 support is implemented.
     */
    public function getBanks(?string $targetUrl = null): EpsSOBankListProtocol
    {
        throw new \LogicException('Not implemented yet - waiting for XSD 2.7');
    }

    /**
     * Send a refund request using the v2.7 interface.
     *
     * Not implemented until XSD 2.7 is available.
     *
     * @param RefundRequest $refundRequest Refund request details.
     * @param string|null $targetUrl Optional custom target URL instead of the default.
     * @return EpsRefundResponse
     * @throws \LogicException Always thrown until v2.7 support is implemented.
     */
    public function sendRefundRequest(
        RefundRequest $refundRequest,
        ?string       $targetUrl = null
    ): EpsRefundResponse
    {
        throw new \LogicException('Not implemented yet - waiting for XSD 2.7');
    }
}
