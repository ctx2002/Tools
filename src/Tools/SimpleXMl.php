<?php
namespace Tools;

class SimpleXMl 
{
    private $root;
    public function __construct($rootName,$ns="")
    {
        $this->root = new \SimpleXMLElement("<".$rootName." ".$ns."></".$rootName.">");
    }
    
    public function getRoot()
    {
        return $this->root;
    }
    
    public function getParent()
    {
        return $this->root->xpath('..');
    }
    
    public function setElementAttribute($key,$value,$namespace=null)
    {
        $this->root->addAttribute($key, $value, $namespace);
        return $this;
    }
    
    public function setElement($name,$value = null,$namespace=null)
    {
        $this->root = $this->root->addChild($name, $value,$namespace);
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
$xml = new SimpleXML("root",'xmlns:android="http://schemas.android.com/apk/res/android"');
$xml->setElementAttribute("version", "1.0.0")
        ->setElement("cars")
            ->setElement("honda")->setElementAttribute("android:id", 1,"http://schemas.android.com/apk/res/android")
                ->setElement("Colour" , "red","http://schemas.android.com/apk/res/android")->end()
            ->end()
            ->setElement("Volvo")->setElementAttribute("id", 1)
                ->setElement("Colour" , "red")->end()
            ->end()
        ->end();
echo $xml->toXML();
 * */
 
