<?php
namespace Tools;
class String {
    private $id;

    public function __construct($value) 
    {
        $this->id = $value;
    }

    public function startWith($char,$offset = 0)
    {
        return $this->id ==="" || strpos($this->id, $char, $offset) === $offset;
    }

    public function endWith($char)
    {
        return $this->id ==="" || strrpos($this->id, $char) === (strlen($this->id) - strlen($char) );
    }

    public function lenght()
    {
        return strlen($this->id);
    }

    public function charAt($pos)
    {
        if ( isset($this->id[$pos]) ) {
            return $this->id[$pos];
        } else {
            return null;
        }
    }

    public function substr($start,$lenght = null)
    {
        if (is_null($lenght)) {
            return substr ($this->id , $start );
        } else {
            return substr ($this->id , $start ,$lenght);
        }
    }

    public function equalsIgnoreCase($other)
    {
        return strncasecmp($this->id, $other, strlen($this->id));
    }
    
    public function concat(\Tools\String $str)
    {
        return new \Tools\String($this->id . $str->__toString());    
    }
    
    public function contains(\Tools\String $str)
    {
        return strpos($this->id, $str->__toString());    
    }
    
    public function match($regex)
    {
        return preg_match($regex, $this->id);
    }
    
    /**
     * @param string|array $regex 
     * @param string|array $replacement
     **/
    public function replace($regex,$replacement)
    {
        $str = preg_replace($regex, $replacement, $this->__toString());
        return new \Tools\String($str);
    }
    
    /**
     * $param string $mask Simply list all characters that 
     *  you want to be stripped. With .. you can specify a range of characters
     **/
    public function trim($mask = " \t\n\r\0\x0B")
    {
        $str = trim($this->__toString() , $character_mask);
        return new \Tools\String( $str );    
    }

    public function __toString() {
        return $this->id;
    }
}
