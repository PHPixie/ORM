<?php

namespace PHPixie\ORM\Loaders\Loader\Repository;

abstract class Database extends \PHPixie\ORM\Loaders\Loader\Repository
{
    protected function loadModel($data)
    {
        return $this->repository->load($data);
    }
}
