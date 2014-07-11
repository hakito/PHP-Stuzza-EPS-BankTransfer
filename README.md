PHP-Stuzza-EPS-BankTransfer
===========================

PHP Implementation of the Stuzza e-payment standard. See http://www.stuzza.at/11351_DE.pdf 

Usage
-----

Following is the minimal set to start a payment request:

```php
require_once('src/autoloader.php');
use at\externet\eps_bank_transfer;

$article = new eps_bank_transfer\WebshopArticle(
  'ArticleName',  // Article name
  1,              // Quantity
  9999            // Price in EUR cents
);

$transferMsgDetails = new eps_bank_transfer\TransferMsgDetails(
  'https://yourdomain.example.com/eps_confirm.php', // The url where the EPS scheme operator will call on payment
  'https://yourdomain.example.com/ThankYou.html',   // The url the buyer will be redirected on succesful payment
  'https://yourdomain.example.com/Failure.html'     // Tge url the buyer will be redirected on cancel or failure
);

$transferInitiatorDetails = new eps_bank_transfer\TransferInitiatorDetails(
  'AKLJS231534',            // Eps "Händler" id
  'topSecret',              // Secret for authentication
  'GAWIATW1XXX',            // BIC code of bank account where money will be sent to
  'John Q. Public',         // Name of the account owner where money will be sent to
  'AT611904300234573201',   // IBAN code of bank account where money will be sent to
  '12345',                  // Reference identifier. This identifies the payment message
  'Order123',               // Remittance identifier. This value will be returned on payment confirmation
  '9999',                   // Total amount in EUR cent
  $article,                 // Array or single webshop article
  $transferMsgDetails);
  
$soCommunicator = new eps_bank_transfer\SoCommunicator();
$plain = $soCommunicator->SendTransferInitiatorDetails($transferInitiatorDetails);
$xml = new SimpleXMLElement($plain);
$soAnswer = $xml->children(eps_bank_transfer\XMLNS_epsp);
$errorDetails = &$soAnswer->BankResponseDetails->ErrorDetails;

if (('' . $errorDetails->ErrorCode) != '000')
{
  $errorCode = '' . $errorDetails->ErrorCode;
  $errorMsg = '' . $errorDetails->ErrorMsg;
}

// This is the url you have to redirect the client to.
$redirectUrl = $soAnswer->BankResponseDetails->ClientRedirectUrl;
header('Location: ' . $redirectUrl);
```

In eps_confirm.php you have to handle the confirmation call from the scheme operator:

```php
require_once('src/autoloader.php');
use at\externet\eps_bank_transfer;

$paymentConfirmationCallback = function($plainXml, $remittanceIdentifier, $statusCode)
{
  if ($statusCode == 'OK')
  {
    // TODO: Do your payment completion handling here
  }
  
  // True is expected to be returned, otherwise the scheme operator will be informed that
  // the server could not accept the payment confirmation
  return true; 
}

$soCommunicator = new eps_bank_transfer\SoCommunicator();
$soCommunicator->HandleConfirmationUrl(
  $paymentConfirmationCallback,
  null,                         // optional callback for vitality check
  'php://input',                // optional the input stream of post data received by the server
  'php://output'                // optional the output stream to send to the scheme operator
);
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
