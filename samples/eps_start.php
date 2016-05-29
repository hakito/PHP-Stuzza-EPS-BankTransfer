<?php
require_once('../vendor/autoloader.php');
use at\externet\eps_bank_transfer;

// Connection credentials. Override them for testing mode. 
$userID = 'AKLJS231534';            // Eps "Händler-ID"/UserID = epsp:UserId
$pin    = 'topSecret';              // Secret for authentication / PIN = part of epsp:MD5Fingerprint
$bic    = 'GAWIATW1XXX';            // BIC code of receiving bank account = epi:BfiBicIdentifier
$iban   = 'AT611904300234573201';   // IBAN code of receiving bank account = epi:BeneficiaryAccountIdentifier
$targetUrl = null; // Target url to send TransferInitiatorDetails to. Default: https://routing.eps.or.at/appl/epsSO-test/transinit/eps/v2_5

// Return urls
$transferMsgDetails = new eps_bank_transfer\TransferMsgDetails(
  'http(s)://yourdomain.example.com/eps_confirm.php', // The URL that the EPS Scheme Operator (=SO) will call before and after payment. Use samples/eps_confirm.php as a starting point.
  'http(s)://yourdomain.example.com/ThankYou.html',   // The URL that the buyer will be redirected to on succesful payment
  'http(s)://yourdomain.example.com/Failure.html'     // The URL that the buyer will be redirected to on cancel or failure
);

$transferInitiatorDetails = new eps_bank_transfer\TransferInitiatorDetails(
  $userID,
  $pin,
  $bic,
  'John Q. Public',         // Name of the receiving account owner = epi:BeneficiaryNameAddressText
  $iban,
  '12345',                  // Reference identifier. Don't show it to the client! This identifies the actual payment = epi:ReferenceIdentifier
  '9999',                   // Total amount in EUR cent ≈ epi:InstructedAmount
  $transferMsgDetails);

// Optional: Include ONE (i.e. not both!) of the following two lines:
$transferInitiatorDetails->RemittanceIdentifier = 'Order123';             // "Zahlungsreferenz". Will be returned on payment confirmation = epi:RemittanceIdentifier
$transferInitiatorDetails->UnstructuredRemittanceIdentifier = 'Order123'; // "Verwendungszweck". Will be returned on payment confirmation = epi:UnstructuredRemittanceIdentifier

// Optional:
$transferInitiatorDetails->SetExpirationMinutes(60);     // Sets ExpirationTimeout. Value must be between 5 and 60

// Optional: Include information about one or more articles = epsp:WebshopDetails
$article = new eps_bank_transfer\WebshopArticle(  // = epsp:WebshopArticle
  'ArticleName',  // Article name
  1,              // Quantity
  9999            // Price in EUR cents
);
$transferInitiatorDetails->WebshopArticles[] = $article;

// Send TransferInitiatorDetails to Scheme Operator
$soCommunicator = new eps_bank_transfer\SoCommunicator();

// Send transfer initiator details to $targetUrl
$plain = $soCommunicator->SendTransferInitiatorDetails($transferInitiatorDetails, $targetUrl);
$xml = new \SimpleXMLElement($plain);
$soAnswer = $xml->children(eps_bank_transfer\XMLNS_epsp);
$errorDetails = $soAnswer->BankResponseDetails->ErrorDetails;

if (('' . $errorDetails->ErrorCode) != '000')
{
  $errorCode = '' . $errorDetails->ErrorCode;
  $errorMsg = '' . $errorDetails->ErrorMsg;
}
else
{
  // This is the url you have to redirect the client to.
  $redirectUrl = $soAnswer->BankResponseDetails->ClientRedirectUrl;
  header('Location: ' . $redirectUrl);
}
?>
