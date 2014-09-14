<?php

namespace PHPixie\ORM\Loaders\Loader\Proxy;

class Preloading extends \PHPixie\ORM\Loaders\Loader\Proxy
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

    public function offsetExists($offset)
    {
        return $this->loader->offsetExists($offset);
    }
    
    public function getByOffset($offset)
    {
        $model = $this->loader->getByOffset($offset);
        $this->preloadModelProperties($model);

        return $model;
    }

    protected function preloadModelProperties($model)
    {
        foreach($this->preloaders as $relationship => $preloader)
            $model->setRelationshipProperty($relationship, $preloader->loadFor($model));
    }
    
}
