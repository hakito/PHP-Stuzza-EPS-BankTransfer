<?php
/**
 * EpxXmlElement
 */
namespace at\externet\eps_bank_transfer;

/**
 * Internal wrapper class of \SimpleXmlElement for easier building eps XML files
 */
class EpsXmlElement
{

    /**
     * Actual SimpleXmlElemnt containt all data
     * @todo replace with http://php.net/manual/en/class.arrayaccess.php
     * @var \SimpleXmlElement 
     * @access private
     * @internal
     */
    private $simpleXml;

    /**
     * @internal 
     */
    public function __construct($data, $options = 0, $data_is_url = false, $ns = "", $is_prefix = false)
    {
        if (is_a($data, "SimpleXMLElement"))
            $this->simpleXml = $data;
        else
            $this->simpleXml = new \SimpleXMLElement($data, $options, $data_is_url, $ns, $is_prefix);
    }

    /**
     * 
     * @param type $rootNode
     * @return EpsXmlElement element
     * @internal 
     */
    public static function CreateEmptySimpleXml($rootNode)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><' . $rootNode . '/>';
        return new EpsXmlElement($xml);
    }

    /**
     * @internal 
     */
    public function __get($name)
    {
        $ret = $this->simpleXml->$name;
        $ename = $ret->getName();
        if ($ename == "")
            $ret = $this->simpleXml->xpath('eps:' . $name);

        return new self($ret);
    }

    /**
     * @internal
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->simpleXml, $name), $arguments);
    }

    /**
     * @internal
     */    
    public function addChild($name, $value = '', $namespace = '')
    {
        $child = $this->simpleXml->addChild($name, $value, $namespace);
        return new self($child);
    }

    /**
     * @internal
     */
    public function AddChildExt($name, $value = '', $namespaceAlias = '')
    {
        $ns = $this->getDocNamespaces();
        $namespace = $namespaceAlias;
        if (array_key_exists($namespaceAlias, $ns))
        {
            $name = $namespaceAlias . ':' . $name;
            $namespace = $ns[$namespace];
        }
        return $this->addChild($name, $value, $namespace);
    }
    
    /**
     * @internal
     */
    public function asXML($filename = null)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($this->simpleXml->asXML());
        $dom->formatOutput = true;
        if ($filename == null)
            return $dom->saveXML();
        return $dom->save($filename);
    }

}