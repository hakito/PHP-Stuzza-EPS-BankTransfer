<?php

declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Helper;

use Psa\EpsBankTransfer\Api\SoCommunicator;
use GuzzleHttp\Psr7\HttpFactory;

trait SoCommunicatorTestTrait
{
    use XmlFixtureTestTrait;

    /** @var SoCommunicator Public API under test */
    protected $target;

    /** @var Psr18TestHttp Mock PSR-18 HTTP client */
    protected $http;

    /**
     * Initialize SoCommunicator with a mock HTTP client and PSR-17 factories.
     *
     * @param string $baseUrl Base URL (LIVE by default) used by the communicator.
     */
    protected function setUpCommunicator(string $baseUrl = SoCommunicator::LIVE_MODE_URL): void
    {
        $this->http = new Psr18TestHttp();
        $factory = new HttpFactory();
        $this->target = new SoCommunicator($this->http, $factory, $factory, $baseUrl);
        date_default_timezone_set('UTC');
    }

    /**
     * Enqueue a fake HTTP response for the next request performed by SoCommunicator.
     *
     * @param int $status HTTP status code
     * @param string $body Response body
     * @param array $headers Response headers (defaults to application/xml)
     */
    protected function mockResponse(int $status, string $body, array $headers = ['Content-Type' => 'application/xml']): void
    {
        $this->http->pushResponse($status, $headers, $body);
    }
}