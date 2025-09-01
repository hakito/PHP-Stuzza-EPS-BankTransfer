<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Utilities;

class Fingerprint
{
    public static function calculateExpectedMD5Fingerprint(string $secret, string $date, string $reference, string $account, string $remittance, int $amount, string $currency, string $userId): string
    {
        return md5($secret . $date . $reference . $account . $remittance . MoneyFormatter::formatXsdDecimal($amount) . $currency . $userId);
    }

    public static function generateSHA256Fingerprint(string $pin, string $creationDateTime, string $transactionId, string $merchantIban, string $amountValue, string $amountCurrency, string $userId, ?string $refundReference = null): string
    {
        $inputData = $pin .
            $creationDateTime .
            $transactionId .
            $merchantIban .
            $amountValue .
            $amountCurrency .
            $refundReference .
            $userId;

        return strtoupper(hash('sha256', $inputData));
    }
}
