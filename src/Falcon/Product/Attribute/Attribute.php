<?php

namespace Falcon\Product\Attribute;

class Attribute
{
    
    const TYPE_OPTION = 0;
    const TYPE_TEXT = 1;
    const TYPE_TEXTAREA = 2;
    const TYPE_RADIO = 3;
    const TYPE_CHECKBOX = 4;
    const TYPE_URL = 6;
    
    const TYPE_SELECT = 50;
    const TYPE_SET_RADIO = 51;
    const TYPE_SET_CHECKBOX = 52;
    
    protected $id;
    protected $label;
    protected $type;
    protected $options;
    protected $children = array();

    public function __construct($id, $label, $type)
    {
        if (!empty($id) && !is_numeric($id)) {
            throw new \InvalidArgumentException('Invalid id detected!');
        }

        if (empty($label)) {
            throw new \InvalidArgumentException('Attribute label cannot be empty');
        }
        
        $reflection = new \ReflectionClass(__CLASS__);
        if (!in_array($type, $reflection->getConstants())) {
            throw new \InvalidArgumentException('Invalid attribute type: ' . $type);
        }
        $this->id = $id;
        $this->label = $label;
        $this->type = $type;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function addChild(Attribute $attr)
    {
        $this->children[] = $attr;
    }
    
    public function getChildren()
    {
        return $this->children;
    }
    
    public function hasChildren()
    {
        return !empty($this->children);
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function getType()
    {
        return $this->type;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
}
