[![Build Status](https://travis-ci.org/hakito/PHP-Stuzza-EPS-BankTransfer.svg?branch=master)](https://travis-ci.org/hakito/PHP-Stuzza-EPS-BankTransfer) [![Coverage Status](https://coveralls.io/repos/hakito/PHP-Stuzza-EPS-BankTransfer/badge.png)](https://coveralls.io/r/hakito/PHP-Stuzza-EPS-BankTransfer)
[![Latest Stable Version](https://poser.pugx.org/hakito/php-stuzza-eps-banktransfer/v/stable.svg)](https://packagist.org/packages/hakito/php-stuzza-eps-banktransfer) [![Total Downloads](https://poser.pugx.org/hakito/php-stuzza-eps-banktransfer/downloads.svg)](https://packagist.org/packages/hakito/php-stuzza-eps-banktransfer) [![Latest Unstable Version](https://poser.pugx.org/hakito/php-stuzza-eps-banktransfer/v/unstable.svg)](https://packagist.org/packages/hakito/php-stuzza-eps-banktransfer) [![License](https://poser.pugx.org/hakito/php-stuzza-eps-banktransfer/license.svg)](https://packagist.org/packages/hakito/php-stuzza-eps-banktransfer)

PHP-Stuzza-EPS-BankTransfer
===========================

PHP Implementation of the Stuzza e-payment standard. See http://www.stuzza.at/12872_DE.pdf?exp=24568045069672&12872

Installation
------------

Create a copy of these folders in your project:

* src
* tests
* XSD

Usage
-----

Look at the following files in the sample folder:

* eps_start.php
* eps_confirm.php

To run the tests go to the parent folder of tests and execute:

```
phpunit
```

Remarks
-------

The current implementation does not support XML certificates and signing. Make sure that the
confirmation url is not easily guessable. Think about adding unique security parameters to the
confirmation url for every transaction.

Donate
------

Any donation is welcome

* PayPal: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XPWL7H2NG3VVL
* Bitcoin: 1JUBqyAJg5igMABtzy1kRM6CLBmmvw5hmi
