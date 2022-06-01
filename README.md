[![Build Status](https://app.travis-ci.com/hakito/PHP-Stuzza-EPS-BankTransfer.svg?branch=master)](https://app.travis-ci.com/hakito/PHP-Stuzza-EPS-BankTransfer) [![Coverage Status](https://coveralls.io/repos/hakito/PHP-Stuzza-EPS-BankTransfer/badge.png)](https://coveralls.io/r/hakito/PHP-Stuzza-EPS-BankTransfer)
[![Latest Stable Version](https://poser.pugx.org/hakito/php-stuzza-eps-banktransfer/v/stable.svg)](https://packagist.org/packages/hakito/php-stuzza-eps-banktransfer) [![Total Downloads](https://poser.pugx.org/hakito/php-stuzza-eps-banktransfer/downloads.svg)](https://packagist.org/packages/hakito/php-stuzza-eps-banktransfer) [![Latest Unstable Version](https://poser.pugx.org/hakito/php-stuzza-eps-banktransfer/v/unstable.svg)](https://packagist.org/packages/hakito/php-stuzza-eps-banktransfer) [![License](https://poser.pugx.org/hakito/php-stuzza-eps-banktransfer/license.svg)](https://packagist.org/packages/hakito/php-stuzza-eps-banktransfer)

# PHP-Stuzza-EPS-BankTransfer

PHP implementation of the Austrian e-payment standard "eps" (Version 2.6), specified by Stuzza. See http://www.stuzza.at/de/component/k2/item/23-eps-ueberweisung.html or http://www.eps-ueberweisung.at/

## Installation

Create a copy of these folders in your project:

* src
* tests
* XSD

Or use composer:

```sh
composer require hakito/php-stuzza-eps-banktransfer
```

## Usage

Look at the following files in the sample folder:

* eps_start.php
* eps_confirm.php

To run the tests, go to the parent folder of tests and execute:

```sh
phpunit
```

## Migration from v1.x

In v2.0 the SoCommunicator uses a common base URL for ApiCalls. This is set per default to
SoCommunicator::LIVE_MODE_URL in the constructor.

```php
use at\externet\eps_bank_transfer\SoCommunicator;
$soCommunicator = new SoCommunicator();
// $soCommunicator->BaseUrl == SoCommunicator::LIVE_MODE_URL;
$soCommunicator = new SoCommunicator(true);
// $soCommunicator->BaseUrl == SoCommunicator::TEST_MODE_URL;
// You can also set BaseUrl to a custom endpoint.
```

Because of this change the URL parameter has been removed for the functions:

* TryGetBanksArray
* GetBanksArray
* GetBanks

## Remarks

The current implementation does not support XML certificates and signing. Make sure that the
confirmation url is not easily guessable. Think about adding unique security parameters to the
confirmation url for every transaction.
