<?php
/*
This file handles the confirmation call from the scheme operator (after a payment was received). It is called twice:
1. for Vitality-Check, according to "Abbildung 6-11: epsp:VitalityCheckDetails" (eps Pflichtenheft 2.5)
2. for the actual payment confirmation (Zahlungsbestätigung)
*/

require_once('src/autoloader.php');
use at\externet\eps_bank_transfer;

/**
 * @param string $plainXml Raw XML message, according to "Abbildung 6-6: PaymentConfirmationDetails" (eps Pflichtenheft 2.5)
 * @param string $remittanceIdentifier
 * @param string $statusCode "eps:StatusCode": "OK" or "NOK" or "VOK" or "UNKNOWN"
 * @return true
 */
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
  null,                 // Optional: a callback function which is called in case of Vitality-Check
  'php://input',        // This needs to be the raw post data received by the server. Change this only if you want to test this function with simulation data.
  'php://output'        // This needs to be the raw output stream which is sent to the Scheme Operator. Change this only if you want to test this function with simulation data.
);
?>
