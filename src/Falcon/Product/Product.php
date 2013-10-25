<?php

namespace Falcon\Product;

use Falcon\Model\LazyModelAbstract;

class Product extends LazyModelAbstract
{
    protected $registry = array(
        'name' => null,
        'year' => null,
        'designer' => null,
        'publisher' => null,
        'sku' => null,
        'upc' => null,
        'msrp' => null
    );
    
    protected $attributes = array();
    
    protected $descriptions = array();
    
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    public function getAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
    }
    
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }
    
    public function getMainImage($basePath = '')
    {
        $id = $this->get('sku');
        return $basePath . '/img/product/' . $id . '.jpg'; 
    }
    
}