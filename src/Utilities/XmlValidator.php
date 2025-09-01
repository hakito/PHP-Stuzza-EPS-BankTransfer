<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Utilities;

use Psa\EpsBankTransfer\Exceptions\XmlValidationException;

class XmlValidator
{
    private const VERSION_MAPPING = [
        '2.6' => 'V26',
        '2.7' => 'V27'
    ];

    /**
     * @throws XmlValidationException
     */
    public static function validateBankList($xml): bool
    {
        return self::validateXml($xml, self::gtXSD('epsSOBankListProtocol.xsd'));
    }

    /**
     * @throws XmlValidationException
     */
    public static function validateEpsProtocol(string $xml, string $version = '2.6'): bool
    {
        $mappedVersion = self::VERSION_MAPPING[$version] ?? 'V26';
        $filename = "EPSProtocol-{$mappedVersion}.xsd";
        return self::validateXml($xml, self::gtXSD($filename));
    }

    /**
     * @throws XmlValidationException
     */
    public static function validateEpsRefund($xml, string $version = '2.6'): bool
    {
        $mappedVersion = self::VERSION_MAPPING[$version] ?? 'V26';
        $filename = "EPSRefund-{$mappedVersion}.xsd";
        return self::validateXml($xml, self::gtXSD($filename));
    }

    private static function gtXSD(string $filename): string
    {
        return dirname(__DIR__, 2)
            . DIRECTORY_SEPARATOR . 'resources'
            . DIRECTORY_SEPARATOR . 'schemas'
            . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @throws XmlValidationException
     */
    private static function validateXml($xml, $xsd): bool
    {
        if (empty($xml)) {
            throw new XmlValidationException('XML is empty');
        }
        $doc = new \DOMDocument();
        try {
            $doc->loadXML($xml);
        } catch (\Exception $e) {
            throw new XmlValidationException('Failed to load XML: ' . $e->getMessage());
        }
        $prevState = libxml_use_internal_errors(true);
        if (!$doc->schemaValidate($xsd)) {
            $xmlError = libxml_get_last_error();
            libxml_use_internal_errors($prevState);

            throw new XmlValidationException('XML does not validate against XSD. ' . $xmlError->message);
        }
        libxml_use_internal_errors($prevState);
        return true;
    }
}