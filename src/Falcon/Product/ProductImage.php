<?php

namespace Falcon\Product;

use Falcon\Model\LazyModelAbstract;

class ProductImage extends LazyModelAbstract
{
    
    protected $registry = array(
        'product_id' => null,
        'file_path' => null,
        'width' => null,
        'height' => null
    );
}
