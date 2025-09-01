# EPS Bank Transfer (psa/eps-bank-transfer)

A PHP library for integrating the Austrian EPS bank transfer (PSA, specification v2.6, with preparation for v2.7). It
helps you start EPS payments, handle the confirmation (callback/return), and trigger refunds (full or partial).
Note on origins: This project was initially based on hakito/php-stuzza-eps-banktransfer. The codebase was rewritten but
has the same core functionality. Credit to the original authors for the concept and initial implementation.

Links:
- Original code: https://github.com/hakito/PHP-Stuzza-EPS-BankTransfer
- Specification/Info: https://www.eps-ueberweisung.at/

## Installation

Install via Composer:

```sh
composer require psa/eps-bank-transfer
```

## What is this library for?

- Start an EPS payment: Create a payment request and redirect customers to the bank list/bank.
- Receive confirmation (return/callback): Verify the transaction status and update your order.
- Refund: Trigger a full or partial refund.

The library wraps the required requests/responses generated from the official XSD schemas and provides PSR-compatible HTTP communication.

## Quick start

Check the examples in the `samples/` folder:
- `samples/eps_start.php` – Start a payment (fetch bank list, initialize payment)
- `samples/eps_confirm.php` – Process the confirmation callback
- `samples/eps_refund.php` – Trigger a refund

### Basic configuration (Test/Live)

The `SoCommunicator` provides endpoint URLs as constants: `TEST_MODE_URL` for test environments and
`LIVE_MODE_URL` for production usage. Choose the appropriate URL for your environment.

Example:
```php
use Psa\EpsBankTransfer\Api\SoCommunicator;
use GuzzleHttp\Client;
use Http\Discovery\Psr17FactoryDiscovery;

$isTestMode = true;
$url = $isTestMode ? SoCommunicator::TEST_MODE_URL : SoCommunicator::LIVE_MODE_URL;

$requestFactory = Psr17FactoryDiscovery::findRequestFactory();
$streamFactory = Psr17FactoryDiscovery::findStreamFactory();
$soCommunicator = new SoCommunicator(
    new Client(['verify' => true]),
    $requestFactory,
    $streamFactory,
    $url
);
```

## Typical flow

1. Get bank list (optional): Show available banks to the user.
2. Start payment: Create the start request with amount, currency, order ID, and return URLs.
3. User is redirected to the bank/e-banking.
4. Process callback/return: Validate status, amount, order ID; update your order status.
5. Optional: Perform a refund.

The examples under `samples/` demonstrate this end-to-end flow.

## Security and limitations

- XML signatures/certificates: Not supported at the moment. Make sure your confirmation URL is hard to guess (e.g., unique tokens per transaction).
- TLS/verification: Keep certificate verification enabled (`['verify' => true]`).
- Idempotency: Implement idempotent handling for callbacks and refunds.

## Generated classes from XSD (background & generation)

Requests/responses are generated from the official XSD schemas. This ensures type safety and compatibility with the EPS standard. If schemas change or you need adjustments, you can regenerate the PHP classes:

```sh
vendor/bin/xsd2php convert xsd2php.yaml resources/schemas/*.xsd
```

- `xsd2php.yaml` contains the mapping configuration.
- Schemas live under `resources/schemas`.
- Generated classes are placed under `src/Internal/Generated/...` namespaces and used by the serializer.

## Tests

PHPUnit is configured. Run:

```sh
vendor/bin/phpunit
```

## License

GPL-2.0 – see LICENSE.

## Credits

- Project initially started by @hakito: https://github.com/hakito/PHP-Stuzza-EPS-BankTransfer