<?php

namespace Falcon\Product;

interface ProductRepoAwareInterface
{
    public function setProductRepo(ProductRepo $repo);
}
