<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Serializer;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

class SerializerFactory
{
    /** @var SerializerInterface|null */
    private static $instance;

    public static function create(): SerializerInterface
    {
        if (self::$instance instanceof SerializerInterface) {
            return self::$instance;
        }

        $projectRoot = dirname(__DIR__, 2);

        self::$instance = SerializerBuilder::create()
            ->setSerializationVisitor('xml', new NoCdataXmlSerializationVisitorFactory())
            ->addMetadataDir($projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'Protocol' . DIRECTORY_SEPARATOR . 'V26', 'Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V26')
            ->addMetadataDir($projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'Protocol' . DIRECTORY_SEPARATOR . 'V27', 'Psa\\EpsBankTransfer\\Internal\\Generated\\Protocol\\V27')
            ->addMetadataDir($projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'Payment' . DIRECTORY_SEPARATOR . 'V26', 'Psa\\EpsBankTransfer\\Internal\\Generated\\Payment\\V26')
            ->addMetadataDir($projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'Payment' . DIRECTORY_SEPARATOR . 'V27', 'Psa\\EpsBankTransfer\\Internal\\Generated\\Payment\\V27')
            ->addMetadataDir($projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'AustrianRules', 'Psa\\EpsBankTransfer\\Internal\\Generated\\AustrianRules')
            ->addMetadataDir($projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'Epi', 'Psa\\EpsBankTransfer\\Internal\\Generated\\Epi')
            ->addMetadataDir($projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'Refund', 'Psa\\EpsBankTransfer\\Internal\\Generated\\Refund')
            ->addMetadataDir($projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'BankList', 'Psa\\EpsBankTransfer\\Internal\\Generated\\BankList')
            ->addMetadataDir($projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'XmlDsig', 'Psa\\EpsBankTransfer\\Internal\\Generated\\XmlDsig')
            ->setDebug(true)
            ->build();

        return self::$instance;
    }
}