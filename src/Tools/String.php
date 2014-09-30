<?php
namespace Tools;
class String {
    private $id;

    public function __construct($value) {
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


    public function __toString() {
        return $this->id;
    }
}
