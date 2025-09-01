<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Internal\V26;

use Exception;
use Psa\EpsBankTransfer\Domain\BankConfirmationDetails;
use Psa\EpsBankTransfer\Domain\VitalityCheckDetails;
use Psa\EpsBankTransfer\Exceptions\CallbackResponseException;
use Psa\EpsBankTransfer\Exceptions\EpsException;
use Psa\EpsBankTransfer\Exceptions\InvalidCallbackException;
use Psa\EpsBankTransfer\Exceptions\XmlValidationException;
use Psa\EpsBankTransfer\Internal\Generated\BankList\EpsSOBankListProtocol;
use Psa\EpsBankTransfer\Internal\Generated\Protocol\V26\EpsProtocolDetails;
use Psa\EpsBankTransfer\Internal\Generated\Refund\EpsRefundResponse;
use Psa\EpsBankTransfer\Internal\SoCommunicatorCore;
use Psa\EpsBankTransfer\Requests\RefundRequest;
use Psa\EpsBankTransfer\Requests\TransferInitiatorDetails;
use Psa\EpsBankTransfer\Responses\ShopResponseDetails;
use Psa\EpsBankTransfer\Utilities\XmlValidator;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Internal communicator for EPS interface version 2.6.
 *
 * Encapsulates the low-level HTTP calls, XML serialization and validation
 * required by the EPS Scheme Operator for v2.6 endpoints (bank list, transfer
 * initiator, refund, confirmations).
 */
class SoV26Communicator
{
    public const BANKLIST = '/data/haendler/v2_6';
    public const REFUND   = '/refund/eps/v2_6';
    public const TRANSFER = '/transinit/eps/v2_6';
    public const VERSION = '2.6';

    /** @var SoCommunicatorCore */
    private $core;

    /** @var SerializerInterface */
    private $serializer;

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
     * @throws XmlValidationException
     * @throws Exception
     */
    public function sendTransferInitiatorDetails(
        TransferInitiatorDetails $transferInitiatorDetails,
        ?string                  $targetUrl = null
    ): EpsProtocolDetails {
        if ($transferInitiatorDetails->getRemittanceIdentifier() !== null) {
            $transferInitiatorDetails->setRemittanceIdentifier(
                $this->core->appendHash(
                    $transferInitiatorDetails->getRemittanceIdentifier(),
                    $transferInitiatorDetails->getObscuritySuffixLength(),
                    $transferInitiatorDetails->getObscuritySeed(),
                )
            );
        }

        if ($transferInitiatorDetails->getUnstructuredRemittanceIdentifier() !== null) {
            $transferInitiatorDetails->setUnstructuredRemittanceIdentifier(
                $this->core->appendHash(
                    $transferInitiatorDetails->getUnstructuredRemittanceIdentifier(),
                    $transferInitiatorDetails->getObscuritySuffixLength(),
                    $transferInitiatorDetails->getObscuritySeed()
                )
            );
        }

        $targetUrl = $targetUrl ?? $this->core->getBaseUrl() . SoV26Communicator::TRANSFER;

        $xmlData = $this->serializer->serialize($transferInitiatorDetails->toV26(), 'xml');
        $response = $this->core->postUrl($targetUrl, $xmlData, 'Send payment order');

        XmlValidator::validateEpsProtocol($response, self::VERSION);

        return $this->serializer->deserialize($response, EpsProtocolDetails::class, 'xml');
    }

    /**
     * @throws InvalidCallbackException
     * @throws XmlValidationException
     * @throws CallbackResponseException
     */
    public function handleConfirmationUrl(
        $confirmationCallback = null,
        $vitalityCheckCallback = null,
        string $rawPostStream = 'php://input',
        string $outputStream = 'php://output'
    ): void
    {
        try {
            if ($confirmationCallback === null || !is_callable($confirmationCallback)) {
                throw new InvalidCallbackException('ConfirmationCallback not callable or missing');
            }
            if ($vitalityCheckCallback !== null && !is_callable($vitalityCheckCallback)) {
                throw new InvalidCallbackException('VitalityCheckCallback not callable');
            }

            $rawXml = file_get_contents($rawPostStream);
            XmlValidator::validateEpsProtocol($rawXml, self::VERSION);

            $protocol = $this->serializer->deserialize($rawXml, EpsProtocolDetails::class, 'xml');

            if ($protocol->getVitalityCheckDetails() !== null) {
                $this->handleVitalityCheck(
                    $vitalityCheckCallback,
                    $rawXml,
                    VitalityCheckDetails::fromV26($protocol->getVitalityCheckDetails()),
                    $outputStream
                );
                return;
            }

            if ($protocol->getBankConfirmationDetails() !== null) {
                $this->handleBankConfirmation(
                    $confirmationCallback,
                    $rawXml,
                    BankConfirmationDetails::fromV26($protocol),
                    $outputStream);
                return;
            }

            throw new XmlValidationException('Unknown confirmation details structure');

        } catch (Exception $e) {
            $this->handleException($e, $outputStream);
            throw $e;
        }
    }

    /**
     * @throws XmlValidationException
     */
    public function getBanks(?string $targetUrl = null): EpsSOBankListProtocol
    {
        $targetUrl = $targetUrl ?? $this->core->getBaseUrl() . SoV26Communicator::BANKLIST;
        $body = $this->core->getUrl($targetUrl, 'Requesting bank list');

        XmlValidator::validateBankList($body);

        return $this->serializer->deserialize($body, EpsSOBankListProtocol::class, 'xml');
    }

    /**
     * @throws XmlValidationException
     * @throws Exception
     */
    public function sendRefundRequest(
        RefundRequest $refundRequest,
        ?string       $targetUrl = null
    ): EpsRefundResponse
    {
        $targetUrl = $targetUrl ?? $this->core->getBaseUrl() . SoV26Communicator::REFUND;

        $xmlData = $this->serializer->serialize($refundRequest->toV26(), 'xml');
        $responseXml = $this->core->postUrl(
            $targetUrl,
            $xmlData,
            'Sending refund request to ' . $targetUrl
        );

        XmlValidator::validateEpsRefund($responseXml, self::VERSION);

        return $this->serializer->deserialize($responseXml, EpsRefundResponse::class, 'xml');
    }

    /**
     * @throws CallbackResponseException
     */
    private function handleVitalityCheck(?callable $callback, string $rawXml, VitalityCheckDetails $vitality, string $outputStream): void
    {
        if ($callback !== null) {
            if (call_user_func($callback, $rawXml, $vitality) !== true) {
                throw new CallbackResponseException('Vitality check callback must return true');
            }
        }
        file_put_contents($outputStream, $rawXml);
    }

    /**
     * @throws CallbackResponseException
     */
    private function handleBankConfirmation(callable $callback, string $rawXml,
                                            BankConfirmationDetails $confirmation,
                                            string $outputStream): void
    {
        $shopConfirmationDetails = new ShopResponseDetails();
        $shopConfirmationDetails->setSessionId($confirmation->getSessionId());
        $shopConfirmationDetails->setStatusCode($confirmation->getStatusCode());
        $shopConfirmationDetails->setPaymentReferenceIdentifier(
            $confirmation->getPaymentReferenceIdentifier()
        );

        if (call_user_func($callback, $rawXml, $confirmation) !== true) {
            throw new CallbackResponseException('Confirmation callback must return true');
        }

        $xml = $this->serializer->serialize($shopConfirmationDetails->toV26(), 'xml');
        file_put_contents($outputStream, $xml);
    }

    private function handleException(Exception $e, string $outputStream): void
    {
        $shopConfirmationDetails = new ShopResponseDetails();

        if ($e instanceof EpsException) {
            $shopConfirmationDetails->setErrorMessage($e->getMessage());
        } else {
            $shopConfirmationDetails->setErrorMessage('Exception "' . get_class($e) . '" occurred during confirmation handling');
        }

        file_put_contents($outputStream, $this->serializer->serialize($shopConfirmationDetails->toV26(), 'xml'));
    }
}
