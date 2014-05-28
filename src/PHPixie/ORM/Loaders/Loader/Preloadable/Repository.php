<?php

namespace PHPixie\ORM\Loaders\Loader\Preloadable;

abstract class Repository extends \PHPixie\ORM\Loaders\Loader\Preloadable
{
    protected $repository;

    public function __construct($loaders, $repository)
    {
        parent::__construct($loaders);
        $this->repository = $repository;
    }
    
    public function repository()
    {
        return $this->repository;
    }
    
    protected function loadModel($data)
    {
        return $this->repository->load($data);
    }
}
