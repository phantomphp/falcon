<?php

namespace Falcon\Model;

abstract class LazyModelAbstract extends ModelAbstract
{
    
    protected $registry;
    
    protected $whitelistedKeys = array('id', 'uuid');
    
    public function __construct($id, $uuid, $data)
    {
        parent::__construct($id, $uuid);
        foreach ($data as $key => $val) {
            $this->set($key, $val);
        }
    }
    
    public function get($key)
    {
        return $this->registry[$key];
    }
    
    public function set($key, $val)
    {
        if (!array_key_exists($key, $this->registry) && !in_array($key, $this->whitelistedKeys)) {
            throw new \InvalidArgumentException('Key not found in registry: ' . $key);
        }
        $this->registry[$key] = $val;
    }
}