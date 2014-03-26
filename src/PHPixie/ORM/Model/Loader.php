<?php

namespace PHPixie\ORM\Model;

class Loader
{
    protected $repository;
    protected $preloaders = array();

    public function __construct($repository)
    {
        $this->repository = $loader;
    }

    public function getPreloader($property)
    {
        if (isset($this->preloaders[$property]))
            return $this->preloaders[$property];
        return null;
    }

    public function addPreloader($property, $preloader)
    {
        $this->preloaders[$property] = $preloader;
    }

    public function load($data)
    {
        $model = $this->repository->load($data);
        foreach($preloaders as $property => $preloader)
            $model->$property->setValue($preloader->loadFor($model));
    }
}
