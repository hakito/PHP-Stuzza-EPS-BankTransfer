<?php
require_once('src/autoloader.php');
use at\externet\eps_bank_transfer;

$transferMsgDetails = new eps_bank_transfer\TransferMsgDetails(
  'http(s)://yourdomain.example.com/eps_confirm.php', // The URL that the EPS Scheme Operator (=SO) will call on payment
  'http(s)://yourdomain.example.com/ThankYou.html',   // The URL that the buyer will be redirected to on succesful payment
  'http(s)://yourdomain.example.com/Failure.html'     // The URL that the buyer will be redirected to on cancel or failure
);

// Test Mode: EPS does have a test mode. Ask your bank for the required login credentials. Modify the following block accordingly:
// Use this URL to retrieve the banks' list (see chapter 7.1 in "eps Pflichtenheft 2.5"): 'https://routing.eps.or.at/appl/epsSO-test/data/haendler/v2_5';
$mode = 'test';
if ($mode=='test')
{
	$userID    = '';
	$pin       = ''; 
	$bic       = '';
	$iban      = '';
	$targetUrl = 'https://routing.eps.or.at/appl/epsSO-test/transinit/eps/v2_5';
	echo "<h1>EPS Test Mode</h1>\n";
}
else
{
	$userID = 'AKLJS231534',            // Eps "Händler-ID"/UserID = epsp:UserId
	$pin    = 'topSecret',              // Secret for authentication / PIN
	$bic    = 'GAWIATW1XXX',            // BIC code of receiving bank account = epi:BfiBicIdentifier
	$iban   = 'AT611904300234573201',   // IBAN code of receiving bank account = epi:BeneficiaryAccountIdentifier
}
$transferInitiatorDetails = new eps_bank_transfer\TransferInitiatorDetails(
  $userID,
  $pin,
  $bic,
  'John Q. Public',         // Name of the receiving account owner = epi:BeneficiaryNameAddressText
  $iban,
  '12345',                  // Reference identifier. This identifies the actual payment = epi:ReferenceIdentifier
  '9999',                   // Total amount in EUR cent ≈ epi:InstructedAmount
  $transferMsgDetails);

// Optional: Include ONE (i.e. not both!) of the following two lines:
$transferInitiatorDetails->RemittanceIdentifier = 'Order123';             // "Zahlungsreferenz". Will be returned on payment confirmation = epi:RemittanceIdentifier
$transferInitiatorDetails->UnstructuredRemittanceIdentifier = 'Order123'; // "Verwendungszweck". Will be returned on payment confirmation = epi:UnstructuredRemittanceIdentifier

// Optional:
$transferInitiatorDetails->SetExpirationMinutes(60);     // Sets ExpirationTimeout. Value must be between 5 and 60

// Optional: Include information about the articles = epsp:WebshopDetails
$article = new eps_bank_transfer\WebshopArticle(  // = epsp:WebshopArticle
  'ArticleName',  // Article name
  1,              // Quantity
  9999            // Price in EUR cents
);
$transferInitiatorDetails->WebshopArticles[] = $article;

// Optional: Add more articles like this:
$article = new eps_bank_transfer\WebshopArticle(
  'Second Item',
  3,
  8888
);
$transferInitiatorDetails->WebshopArticles[] = $article;

// Take a look at the wiki to see how ReferenceIdentifier, RemittanceIdentifier and WebshopDetails are displayed to the buyer in various banks' online banking system

// Optional: If you want to display the actual XML file that is being sent to the Scheme Operator:
// echo $transferInitiatorDetails->GetSimpleXml()->asXML();

// Send TransferInitiatorDetails to Scheme Operator
$soCommunicator = new eps_bank_transfer\SoCommunicator();
// Will use the Scheme Operator's website for letting the buyers choose their bank:
$plain = $soCommunicator->SendTransferInitiatorDetails($transferInitiatorDetails, $targetUrl); // Second argument ($targetUrl=null) is optional (only relevant for test mode)
// If you've let the buyer choose on your own website, use the following line instead:
// $plain = $soCommunicator->SendTransferInitiatorDetails($transferInitiatorDetails, $epsUrl); // You need to find out $epsUrl for each bank in advance
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
