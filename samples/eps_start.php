<?php
// This is the minimal set to start a payment request:

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

// optional:
$transferInitiatorDetails->SetExpirationMinutes(60); // Sets ExpirationTimeout. Value must be between 5 and 60

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
?>
