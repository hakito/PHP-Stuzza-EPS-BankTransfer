<?php
/*
This file handles the refund process of a previous EPS payment
*/

require_once('../vendor/autoload.php');

use at\externet\eps_bank_transfer;

$userID = 'AKLJS231534';            // Eps "Händler-ID"/UserID = epsr:UserId
$pin = 'topSecret';                 // Secret for authentication / PIN = part of epsr:SHA256Fingerprint
$merchantIban = 'AT611904300234573201';

$refundRequest = new eps_bank_transfer\EpsRefundRequest(
    date('Y-m-d\TH:i:s'),   // Current date-time. Must not diverge more than 3hrs from SO time
    'epsM7DPP3R12',         // EPS Transaction ID from epsp:BankResponse
    $merchantIban,
    "12.00",                // Amount to refund. Must be lower or equal the original transaction amount
    'EUR',                  // Currency for the amount. EPS Refund 1.0.1 only accepts EUR
    $userID,
    $pin,
    'Refund Reason'         // RefundReference (optional) = Auftraggeberreferenz
);

$testMode = "yes";
$soCommunicator = new eps_bank_transfer\SoCommunicator($testMode == "yes");
$refundResponse = $soCommunicator->SendRefundRequest($refundRequest);

$xml = new \SimpleXMLElement($refundResponse);
$soAnswer = $xml->children(eps_bank_transfer\XMLNS_epsr);

echo $soAnswer->StatusCode . ', ' . $soAnswer->ErrorMsg;
// Return code 000 (No Errors) only means the refund request was accepted by the bank.
// A manual approval might be required.
?>
