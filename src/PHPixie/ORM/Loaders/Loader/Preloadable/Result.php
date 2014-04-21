<?php

namespace PHPixie\ORM\Loaders\Loader\Preloadable;

abstract class Result extends \PHPixie\ORM\Loaders\Loader\Preloadable
{
    protected $repository;

    public function __construct($loaders, $repository)
    {
        parent::__construct($loaders);
        $this->repository = $repository;
    }

    protected function loadModel($data)
    {
        return $this->repository->loadModel($data);
    }
}
