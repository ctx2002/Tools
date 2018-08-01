<?php
namespace Tools;
class AString {
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

    public function equalsIgnoreCase(AString $other)
    {
        
        $bool = false;
        $result = strncasecmp($this->__toString(), 
                $other->__toString(), 
                strlen($this->__toString()));
        
        if (0 === $result) {
            $bool = true;
        }
        
        return $bool;
    }
    
    public function concat(AString $str)
    {
        return new AString($this->id . $str->__toString());
    }
    
    public function contains(AString $str)
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
     *
     * @return AString
     */
    public function replace($regex,$replacement)
    {
        $str = preg_replace($regex, $replacement, $this->__toString());
        return new AString($str);
    }

    /**
     * $param string $mask Simply list all characters that
     *  you want to be stripped. With .. you can specify a range of characters
     * @param string $mask
     * @return AString
     */
    public function trim($mask = " \t\n\r\0\x0B")
    {
        $str = trim($this->__toString() , $mask);
        return new AString( $str );
    }
    
    public function removeLeadChar($str)
    {
        if ($str == '') return '';
        $str[0] = ' ';
        return ltrim($str);
    }
    
    pubic funcion toUnderScore()
    {
        return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), 
                                array('\\1_\\2’, '\\1_\\2'), str_replace('_', '.', $this->id)));
    }

    public function __toString() {
        return $this->id;
    }
}
