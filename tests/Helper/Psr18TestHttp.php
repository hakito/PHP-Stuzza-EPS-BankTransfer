<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Helper;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * HTTP client mock for testing PSR-18 implementations
 */
class Psr18TestHttp implements ClientInterface
{
    /** @var ResponseInterface[] Queue of responses to return */
    private $queue = [];

    /** @var array History of requests received */
    private $history = [];

    /**
     * Sends a PSR-7 request and returns a PSR-7 response
     *
     * @param RequestInterface $request Request to send
     * @return ResponseInterface Response received
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->history[] = $request;
        if (empty($this->queue)) {
            return new Response(200, ['Content-Type' => 'text/plain'], 'OK');
        }
        return array_shift($this->queue);
    }

    /**
     * Adds a response to the queue to be returned by sendRequest()
     *
     * @param int $status HTTP status code
     * @param array $headers Response headers
     * @param string $body Response body
     */
    public function pushResponse(int $status = 200, array $headers = [], string $body = ''): void
    {
        $this->queue[] = new Response($status, $headers, $body);
    }

    /**
     * Gets information about the last request received
     *
     * @return array|null Array containing method, url, headers and body of the last request, or null if no requests
     */
    public function getLastRequestInfo(): ?array
    {
        if (empty($this->history)) {
            return null;
        }
        /** @var RequestInterface $req */
        $req = end($this->history);
        return [
            'method' => $req->getMethod(),
            'url' => (string)$req->getUri(),
            'headers' => $req->getHeaders(),
            'body' => (string)$req->getBody(),
        ];
    }
}