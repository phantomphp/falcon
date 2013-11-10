<?php

namespace Falcon\Product\Attribute;

class AttributeCollection implements \Iterator, \Countable
{
    protected $attributes = array();

    protected $position;
    
    public function __construct()
    {
        $this->position = 0;
    }
    
    public function count()
    {
        return count($this->attributes);
    }
    
    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->attributes[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->attributes[$this->position]);
    }
        
    public function add(Attribute $attr)
    {
        $this->attributes[] = $attr;
    }
    
    public function findByName($name)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getLabel() == $name) {
                return $attribute;
            } elseif ($attribute->hasChildren()) {
                foreach ($attribute->getChildren() as $child) {
                    if ($child->getLabel() == $name) {
                        return $child;
                    }
                }
            }
        }
        
        throw new \RuntimeException('Invalid atrribute name provided: ' . $name);
    }
    
    public function findById($id)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getId() == $id) {
                return $attribute;
            } elseif ($attribute->hasChildren()) {
                foreach ($attribute->getChildren() as $child) {
                    if ($child->getId() == $id) {
                        return $child;
                    }
                }
            }
        }
        
        throw new \RuntimeException('Invalid id provided!');
    }
}
