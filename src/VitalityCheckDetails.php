<?php

namespace at\externet\eps_bank_transfer;

class VitalityCheckDetails
{

    /** @var \SimpleXMLElement */
    public $simpleXml;

    private $remittanceIdentifier;

    public function __construct($simpleXml)
    {
        $this->simpleXml = $simpleXml;
        $this->init($this->simpleXml);
    }

    /**
     *
     * @param \SimpleXMLElement $simpleXml
     */
    private function init($simpleXml)
    {
        $epspChildren = $simpleXml->children(XMLNS_epsp);
        $VitalityCheckDetails = $epspChildren[0];
        $t2 = $VitalityCheckDetails->children(XMLNS_epi);
        $this->remittanceIdentifier = null;

        if (isset($t2->RemittanceIdentifier))
        {
            $this->SetRemittanceIdentifier($t2->RemittanceIdentifier);
        }
        elseif (isset($t2->UnstructuredRemittanceIdentifier))
        {
            $this->SetRemittanceIdentifier($t2->UnstructuredRemittanceIdentifier);
        }
        if ($this->remittanceIdentifier == null)
        {
            throw new \LogicException('Could not find RemittanceIdentifier in XML');
        }
    }

    public function SetRemittanceIdentifier($a)
    {
        $this->remittanceIdentifier = (string) $a;
    }
    
    /**
     * Gets epi:RemittanceIdentifier or epi:UnstructuredRemittanceIdentifier - depending on which one is present in the XML file
     */
    public function GetRemittanceIdentifier()
    {
        return $this->remittanceIdentifier;
    }
}
