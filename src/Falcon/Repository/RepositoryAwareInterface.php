<?php

namespace Falcon\Repository;

interface RepositoryAwareInterface
{
    public function setRepository(RepositoryInterface $repository);
}
