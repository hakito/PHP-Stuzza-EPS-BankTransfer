<?php
/*
This file handles the confirmation call from the Scheme Operator (after a payment was received). It is called twice:
1. for Vitality-Check, according to "Abbildung 6-11: epsp:VitalityCheckDetails" (eps Pflichtenheft 2.5)
2. for the actual payment confirmation (Zahlungsbestätigung)
*/

require_once('../vendor/autoload.php');

use at\externet\eps_bank_transfer;

$userID = 'AKLJS231534';            // Eps "Händler-ID"/UserID = epsp:UserId
$pin = 'topSecret';              // Secret for authentication / PIN = part of epsp:MD5Fingerprint
$merchantIban = 'AT611904300234573201';

$refundRequest = new eps_bank_transfer\EpsRefundRequest(
    date('Y-m-d\TH:i:s'),
    'epsM7DPP3R12',
    $merchantIban,
    "12.00",
    'EUR',
    $userID,
    $pin,
    'Refund Reason' // RefundReference (optional)
);

$testMode = "yes";
$soCommunicator = new eps_bank_transfer\SoCommunicator($testMode == "yes");
$refundResponse = $soCommunicator->ProcessRefund($refundRequest);

$xml = new \SimpleXMLElement($refundResponse);
$soAnswer = $xml->children(eps_bank_transfer\XMLNS_epsr);

echo $soAnswer->StatusCode . ', ' . $soAnswer->ErrorMsg;
?>
