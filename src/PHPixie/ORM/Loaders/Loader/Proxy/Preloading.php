<?php

namespace PHPixie\ORM\Loaders\Loader\Proxy;

class Preloading extends \PHPixie\ORM\Loaders\Loader\Proxy
                 implements \PHPixie\ORM\Mappers\Preload\Preloadable
{
    protected $preloaders = array();

    public function addPreloader($relationship, $preloader)
    {
        $this->preloaders[$relationship] = $preloader;
    }

    public function offsetExists($offset)
    {
        return $this->loader->offsetExists($offset);
    }
    
    public function getByOffset($offset)
    {
        $entity = $this->loader->getByOffset($offset);
        $this->preloadEntityProperties($entity);

        return $entity;
    }

    protected function preloadEntityProperties($entity)
    {
        foreach($this->preloaders as $relationship => $preloader) {
            $property = $entity->getRelationshipProperty($relationship);
            $preloader->loadProperty($property);
        }
    }
    
}
