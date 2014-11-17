<?php

namespace PHPixie\ORM\Loaders\Loader\Repository;

abstract class Embedded extends \PHPixie\ORM\Loaders\Loader\Repository
{
    protected function loadModel($document)
    {
        $model = $this->repository->loadFromDocument($document);
        return $model;
    }
}
