<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Serializer;

use JMS\Serializer\Visitor\Factory\SerializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\XmlSerializationVisitorFactory;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class NoCdataXmlSerializationVisitorFactory implements SerializationVisitorFactory
{
    /** @var XmlSerializationVisitorFactory */
    private $innerFactory;

    public function __construct()
    {
        $this->innerFactory = new XmlSerializationVisitorFactory();
    }

    public function getVisitor(): SerializationVisitorInterface
    {
        $visitor = $this->innerFactory->getVisitor();
        return new NoCdataXmlSerializationVisitor($visitor);
    }
}