PHP-Stuzza-EPS-BankTransfer
===========================

PHP Implementation of the Stuzza e-payment standard. See http://www.stuzza.at/11351_DE.pdf

Installation
------------
Copy these files & folders into any folder:
* src
* tests
* XSD
* eps_start.php
* eps_confirm.php
* eps_cronjob.php (optional)

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
