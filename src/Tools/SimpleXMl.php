<?php
namespace Tools;

class SimpleXMl 
{
    private $root;
    public function __construct($rootName)
    {
        $this->root = new \SimpleXMLElement("<".$rootName."></".$rootName.">");
    }
    
    public function getRoot()
    {
        return $this->root;
    }
    
    public function getParent()
    {
        return $this->root->xpath('..');
    }
    
    public function setElementAttribute($key,$value)
    {
        $this->root->addAttribute($key,$value);
        return $this;
    }
    
    public function setElement($name,$value = null)
    {
        $this->root = $this->root->addChild($name, $value);
        return $this;
    }
    
    public function end()
    {
        $p = $this->getParent();
        if (isset($p[0])) {
            $this->root = $p[0];
        }
        return $this;
    }
    public function toXML()
    {
        return $this->root->asXML();
    }
}

/*
$xml = new MyXMLTree("root");
$xml->setElementAttribute("version", "1.0.0")
        ->setElement("cars")
            ->setElement("honda")->setElementAttribute("id", 1)
                ->setElement("Colour" , "red")->end()
            ->end()
            ->setElement("Volvo")->setElementAttribute("id", 1)
                ->setElement("Colour" , "red")->end()
            ->end()
        ->end();
//echo $xml->getRoot()->asXML();
echo $xml->toXML();
 * */
 
