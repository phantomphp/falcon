<?php

namespace Falcon\Product\Attribute;

interface AttributeRepoAwareInterface
{
    public function setAttributeRepo(AttributeRepo $repo);
}
