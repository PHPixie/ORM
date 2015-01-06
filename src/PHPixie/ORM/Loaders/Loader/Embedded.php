<?php

namespace PHPixie\ORM\Loaders\Loader;

abstract class Embedded extends \PHPixie\ORM\Loaders\Loader
{
    protected function loadModel($document)
    {
        $model = $this->repository->loadFromDocument($document);
        return $model;
    }
}
