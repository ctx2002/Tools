<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tools;

class ListObject implements IteratorAggregate
{
    private $list;
    public function __construct(array $list)
    {
        $this->list = $list;    
    }
    
    public function first()
    {
        $item = array();
        if (isset($this->list[0])) {
            $item = $this->list[0];
        }
        return $item;
    }
    
    public function rest()
    {
        $temp = array();
        $total = count($this->list);
        if (count($total) > 1) {
            $temp = function() {
                for($i = 1; $i <=$total; $i++ ) {
                    yield $this->list[$i];
                }
            };   
        }
        
        //iterator_to_array(temp) turn Generator into an array 
        //var_dump(iterator_to_array(temp));
        return $temp;
    }
    
    public function equal($other)
    {
        return $this->list == $other;
    }
    
    public function expensiveEqual($other)
    {
        //expensive operation.
        $foo = serialize($this->list);
        $boo = serialize($other);
        return $foo == $bar;
    }
    
    public function sort($sort_func)
    {
        $temp = $this->list;
        $sort_func($temp);
        return $temp;
    }
    
    public function exchange(array $other)
    {
        $this->list = null;
        $this->list= $other;
    }
    
    public function shallowCopy()
    {
        return clone $this->list;
    }
    
    /***
     *
     * very expensive operation
     * **/
    public function deepCopy()
    {
        return unserialize( serialize($this->list));    
    }
    
    public function getIterator()
    {
        return new ArrayIterator($this->list);
    }
    
    public function length()
    {
        return count($this->list);
    }
    
    public function totalItem()
    {
        $i = 0;
        foreach ($this->list as $item) {
            if (is_array($item)) {
                $i += $this->countArray($item);        
            } else if ($item instanceof ListObject) {
                $i += $this->item->totalItem();
            } else {
                $i += 1;
            }
        }
        return $i;
    }
    
    private function countArray($item)
    {
        $i = 0;
        foreach ($item as $unit) {
            if (is_array($unit)) {
               $i += $this->countArray($unit); 
            } else {
                $i += 1;
            }
        }
        
        return $i;
    }
    
    public function reverse()
    {
        $temp = array();
        $counter = count($this->list);
        for($i = 0; $i < $counter; $i++) {
            $first = $this->list[$i];
            array_push($temp,$first);
        }
        return new ListObject($temp);
    }
    /**
     * which returns a specified-length prefix of a sequence
     * 
     * prefix(4, [1, 2, 3, 5, 7, 11, 13, 17, 19, 23])
     * 
     * [1, 2, 3, 5]
     */
    public function prefix($number)
    {
        $temp = array();
        $counter = count($this->list);
        for($i = 0; $i < $counter && $i < $number; $i++) {
            $temp[] = $this->list[$i];
        }
        return new ListObject($temp);    
    }
    /***
     * which returns the sequence with the prefix of that length taken off:
     * antiprefix(4, [1, 2, 3, 5, 7, 11, 13, 17, 19, 23]);
     * [7, 11, 13, 17, 19, 23]
     */
    public function antiprefix($number)
    {
        $list = array_slice($this->list, $number);
        return new ListObject( $list);
    }
    
    public function suffix($number)
    {
        $list = array_slice($this->list, 0-$number);
        return new ListObject( $list);
    }
}
