<?php

declare(strict_types=1);

namespace Psa\EpsBankTransfer\Tests\Helper;

trait XmlFixtureTestTrait
{
    protected function assertXmlEqualsFixture(string $expectedFile, string $actualXml): void
    {
        $expectedDom = new \DOMDocument();
        $expectedDom->loadXML(self::loadFixture($expectedFile));
        $expectedDom->formatOutput = true;
        $expectedDom->preserveWhiteSpace = false;
        $expectedDom->normalizeDocument();

        $actualDom = new \DOMDocument();
        $actualDom->loadXML($actualXml);
        $actualDom->formatOutput = true;
        $actualDom->preserveWhiteSpace = false;
        $actualDom->normalizeDocument();

        $this->assertEquals(
            $expectedDom->saveXML(),
            $actualDom->saveXML(),
            "Failed asserting that XML in $expectedFile equals actual output"
        );
    }

    protected static function loadFixture(string $filename): string
    {
        $path = self::fixturePath($filename);

        if (!file_exists($path)) {
            throw new \RuntimeException("Fixture file not found: $path");
        }

        return file_get_contents($path);
    }

    protected static function fixturePath(string $filename): string
    {
        return dirname(__DIR__) . '/Fixtures/' . $filename;
    }
}