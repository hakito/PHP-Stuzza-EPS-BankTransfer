<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Internal;

use Psa\EpsBankTransfer\Exceptions\UnknownRemittanceIdentifierException;
use Psa\EpsBankTransfer\Serializer\SerializerFactory;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class SoCommunicatorCore
{
    /** @var ClientInterface */
    private $httpClient;

    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    /** @var SerializerInterface */
    private $serializer;

    /** @var LoggerInterface|null */
    private $logger;

    /** @var string */
    private $baseUrl;

    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        string $baseUrl,
        ?LoggerInterface $logger = null
    ) {
        $this->httpClient     = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory  = $streamFactory;
        $this->baseUrl        = $baseUrl;
        $this->logger         = $logger;
        $this->serializer     = SerializerFactory::create();
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    // ==============
    // CORE HELPERS
    // ==============

    public function getUrl(string $url, string $logMessage = null): string
    {
        $this->logInfo($logMessage ?: 'GET ' . $url);

        try {
            $request = $this->requestFactory->createRequest('GET', $url)
                ->withHeader('Accept', 'application/xml,text/xml,*/*');

            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            $this->logError('GET failed: ' . $e->getMessage());
            throw new RuntimeException('GET request failed: ' . $e->getMessage(), 0, $e);
        }

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            $this->logError(sprintf('GET %s failed with HTTP %d', $url, $response->getStatusCode()));
            throw new RuntimeException(sprintf('GET %s failed with HTTP %d', $url, $response->getStatusCode()));
        }

        $this->logInfo('GET success: ' . $url);
        return (string)$response->getBody();
    }

    public function postUrl(string $url, string $xmlBody, string $logMessage = null): string
    {
        $this->logInfo($logMessage ?: 'POST ' . $url);

        try {
            $stream = $this->streamFactory->createStream($xmlBody);

            $request = $this->requestFactory->createRequest('POST', $url)
                ->withHeader('Content-Type', 'application/xml; charset=UTF-8')
                ->withHeader('Accept', 'application/xml,text/xml,*/*')
                ->withBody($stream);

            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            $this->logError('POST failed: ' . $e->getMessage());
            throw new RuntimeException('POST request failed: ' . $e->getMessage(), 0, $e);
        }

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            $this->logError(sprintf('POST %s failed with HTTP %d', $url, $response->getStatusCode()));
            throw new RuntimeException(sprintf('POST %s failed with HTTP %d', $url, $response->getStatusCode()));
        }

        $this->logInfo('POST success: ' . $url);
        return (string)$response->getBody();
    }

    public function appendHash(string $string, int $obscuritySuffixLength = 0, ?string $obscuritySeed = null): string
    {
        if ($obscuritySuffixLength === 0) {
            return $string;
        }

        if (empty($obscuritySeed)) {
            throw new \UnexpectedValueException('No security seed set when using security suffix.');
        }

        $hash = hash('sha256', $string . $obscuritySeed);
        return $string . substr($hash, 0, $obscuritySuffixLength);
    }

    /**
     * @throws UnknownRemittanceIdentifierException
     */
    public function stripHash(string $suffixed, int $obscuritySuffixLength = 0, ?string $obscuritySeed = null): string
    {
        if ($obscuritySuffixLength === 0) {
            return $suffixed;
        }

        $remittanceIdentifier = substr($suffixed, 0, -$obscuritySuffixLength);

        if ($this->appendHash($remittanceIdentifier, $obscuritySuffixLength, $obscuritySeed) !== $suffixed) {
            throw new UnknownRemittanceIdentifierException(
                'Unknown RemittanceIdentifier supplied: ' . $suffixed
            );
        }

        return $remittanceIdentifier;
    }

    private function logInfo(string $message): void
    {
        if ($this->logger !== null) {
            $this->logger->info('[EPS] ' . $message);
        }
    }

    private function logError(string $message): void
    {
        if ($this->logger !== null) {
            $this->logger->error('[EPS] ' . $message);
        }
    }

    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }
}
