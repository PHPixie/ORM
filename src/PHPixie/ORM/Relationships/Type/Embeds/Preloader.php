<?php

namespace PHPixie\ORM\Relationships\Type\Embeds;

abstract class Preloader extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader
{
    protected $preloaders = array();

    public function addPreloader($relationship, $preloader)
    {
        $this->preloaders[$relationship] = $preloader;
    }

    public function loadProperty($property)
    {
        $entities = $this->getEntities($property);
        foreach($entities as $entity) {
            foreach($this->preloaders as $relationship => $preloader) {
                $entityProperty = $entity->getRelationshipProperty($relationship);
                $preloader->loadProperty($entityProperty);
            }   
        }
    }
    
    abstract protected function getEntities($property);
}
