<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Serializer;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class NoCdataXmlSerializationVisitor implements SerializationVisitorInterface
{
    /** @var SerializationVisitorInterface */
    private $delegateVisitor;

    public function __construct(SerializationVisitorInterface $delegateVisitor)
    {
        $this->delegateVisitor = $delegateVisitor;
    }

    public function visitString(string $stringValue, array $type)
    {
        return $this->delegateVisitor->getDocument()->createTextNode((string) $stringValue);
    }

    public function visitNull($data, array $type) { return $this->delegateVisitor->visitNull($data, $type); }
    public function visitBoolean(bool $data, array $type) { return $this->delegateVisitor->visitBoolean($data, $type); }
    public function visitInteger(int $data, array $type) { return $this->delegateVisitor->visitInteger($data, $type); }
    public function visitDouble(float $data, array $type) { return $this->delegateVisitor->visitDouble($data, $type); }
    public function visitArray(array $data, array $type): void { $this->delegateVisitor->visitArray($data, $type); }
    public function startVisitingObject(ClassMetadata $metadata, object $data, array $type): void { $this->delegateVisitor->startVisitingObject($metadata, $data, $type); }
    public function visitProperty(PropertyMetadata $metadata, $value): void { $this->delegateVisitor->visitProperty($metadata, $value); }
    public function endVisitingObject(ClassMetadata $metadata, object $data, array $type): void { $this->delegateVisitor->endVisitingObject($metadata, $data, $type); }
    public function getResult($data) { return $this->delegateVisitor->getResult($data); }
    public function prepare($data) { return $this->delegateVisitor->prepare($data); }

    public function getDocument() {
        return $this->delegateVisitor->getDocument();
    }

    public function setNavigator(GraphNavigatorInterface $navigator): void
    {
        $this->delegateVisitor->setNavigator($navigator);
    }
}