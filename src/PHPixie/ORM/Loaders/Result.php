<?php

namespace PHPixie\ORM\Loaders;

abstract class Result extends \PHPixie\ORM\Loader
{
    protected $repository;
    
    public function __construct($orm, $repository, $preloaders)
    {
        parent::__construct($orm, $preloaders);
        $this->repository = $repository;
    }
    
    protected function loadModel($data)
    {
        return $this->repository->loadModel($data);
    }
}