<?php

namespace at\externet\eps_bank_transfer;

class RefundRequestTest extends BaseTest
{

    /**
     * @dataProvider refundRequestDataProvider
     */
    public function testGenerateRefundRequestWithReason(
        string  $CreDtTm,
        string  $TransactionId,
        string  $MerchantIBAN,
        float   $Amount,
        string  $AmountCurrencyIdentifier,
        string  $UserId,
        string  $Pin,
        ?string $RefundReference,
        string  $expectedXmlFile
    )
    {

        $epsRefundRequest = new EpsRefundRequest(
            $CreDtTm,
            $TransactionId,
            $MerchantIBAN,
            $Amount,
            $AmountCurrencyIdentifier,
            $UserId,
            $Pin,
            $RefundReference
        );

        $aSimpleXml = $epsRefundRequest->GetSimpleXml();

        $eDom = new \DOMDocument();
        $eDom->loadXML($this->GetEpsData($expectedXmlFile));
        $eDom->formatOutput = true;
        $eDom->preserveWhiteSpace = false;
        $eDom->normalizeDocument();

        $this->assertEquals($eDom->saveXML(), $aSimpleXml->asXML());
    }

    public function refundRequestDataProvider(): array
    {
        return [
            [
                "2018-09-25T08:09:53.454+02:00",
                "epsJMG15K752",
                "AT175700054011014943",
                0.03,
                "EUR",
                "HYPTAT22XXX_143921",
                "fluxkompensator!",
                "REFUND-123456789",
                "RefundRequest.xml"
            ],
            [
                "2018-09-25T08:09:53.454+02:00",
                "epsJMG15K753",
                "AT175700054011014943",
                0.03,
                "EUR",
                "HYPTAT22XXX_143921",
                "fluxkompensator!",
                null,
                "RefundRequestWithoutReason.xml"
            ],
        ];
    }
}