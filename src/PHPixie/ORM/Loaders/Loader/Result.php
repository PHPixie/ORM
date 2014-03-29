<?php

namespace PHPixie\ORM\Loaders\Loader;

abstract class Result extends \PHPixie\ORM\Loaders\Loader
{
    protected $repository;
    
    public function __construct($loaders, $repository, $preloaders)
    {
        parent::__construct($loaders, $preloaders);
        $this->repository = $repository;
    }
    
    protected function loadModel($data)
    {
        return $this->repository->loadModel($data);
    }
}