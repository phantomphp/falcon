<?php

namespace Falcon\Product;

use Falcon\Model\ModelAbstract;

class Product extends ModelAbstract
{
    protected $name;

    public function __construct($id, $uuid, $name)
    {
        parent::__construct($id, $uuid);
        if (empty($name)) {
            throw new \InvalidArgumentException('Product name cannot be empty!');
        }
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

}