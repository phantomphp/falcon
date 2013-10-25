<?php

namespace Falcon\Product\Attribute;

class AttributeCollection implements \Iterator, \Countable
{
    private $elements = array();
    
    private $position = 0;
    
    public function count()
    {
        return count($this->elements);
    }
    
    public function rewind()
    {
        $this->position = 0;
    }
    
    public function current()
    {
        return $this->elements[$this->position];
    }
    
    public function key()
    {
        return $this->position;
    }
    
    public function next()
    {
        return ++$this->position;
    }
    
    public function valid()
    {
        return isset($this->elements[$this->position]);
    }
}
