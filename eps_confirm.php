<?php
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
};

$soCommunicator = new eps_bank_transfer\SoCommunicator();
$soCommunicator->HandleConfirmationUrl(
  $paymentConfirmationCallback,
  null,                         // optional callback for vitality check
  'php://input',                // optional the input stream of post data received by the server
  'php://output'                // optional the output stream to send to the scheme operator
);
?>
