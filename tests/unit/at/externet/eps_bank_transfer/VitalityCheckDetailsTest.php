<?php

namespace at\externet\eps_bank_transfer;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'BaseTest.php';

class VitalityCheckDetailsTest extends BaseTest
{
    /** @var \SimpleXMLElement[] */
    public $simpleXmls;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simpleXmls = new \SimpleXMLElement($this->GetEpsData('VitalityCheckDetails.xml'));
    }

    public function testGetRemittanceIdentifier()
    {
        $t = new VitalityCheckDetails($this->simpleXmls);
        $actual = $t->GetRemittanceIdentifier();

        $this->assertEquals('AT1234567890XYZ', $actual);
    }
}