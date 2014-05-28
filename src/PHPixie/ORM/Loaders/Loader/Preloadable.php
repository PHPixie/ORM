<?php

namespace PHPixie\ORM\Loaders\Loader;

abstract class Preloadable extends \PHPixie\ORM\Loaders\Loader
{
    protected $preloaders = array();

    public function addPreloader($relationship, $preloader)
    {
        $this->preloaders[$relationship] = $preloader;
    }

    public function getPreloader($relationship)
    {
        if(array_key_exists($relationship, $this->preloaders))

            return $this->preloaders[$relationship];

        return null;
    }

    public function getByOffset($offset)
    {
        $model = $this->getModelByOffset($offset);
        $this->preloadModelProperties($model);

        return $model;
    }

    protected function preloadModelProperties($model)
    {
        foreach($this->preloaders as $relationship => $preloader)
            $model->setRelationshipProperty($relationship, $preloader->loadFor($model));
    }
    
    abstract public function getModelByOffset($offset);
}
