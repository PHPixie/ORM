<?php

namespace PHPixie\ORM\Loaders\Loader;

abstract class Preloadable extends \PHPixie\ORM\Loaders\Loader
{
    protected $preloaders;

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

    public function preloadModelProperties($model)
    {
        foreach($this->preloaders as $relationship => $preloader)
            $model->$relationship = $preloader->loadFor($model);
    }

    public function getByOffset($offset)
    {
        $model = $this->getModelByOffset($offset);
        $this->preloadModelProperties($model);

        return $model;
    }

    abstract public function getModelByOffset($offset);
}
