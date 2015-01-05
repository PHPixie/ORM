<?php

namespace PHPixie\ORM\Loaders\Loader\Proxy;

class Preloading extends \PHPixie\ORM\Loaders\Loader\Proxy
                 implements \PHPixie\ORM\Mappers\Preloader\Preloadable
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
        $entity = $this->loader->getByOffset($offset);
        $this->preloadModelProperties($entity);

        return $entity;
    }

    protected function preloadModelProperties($entity)
    {
        foreach($this->preloaders as $relationship => $preloader) {
            $property = $entity->getRelationshipProperty($relationship);
            $preloader->loadProperty($property);
        }
    }
    
}
