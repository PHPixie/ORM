<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple;

abstract class IdMap extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple
{
    protected $idMap = array();
    
    protected function getMappedFor($entity)
    {

        $entityId = $entity->id();
        if(array_key_exists($entityId, $this->idMap)) {
            $ids = $this->idMap[$entityId];
        }else{
            $ids = array();
        }
        
        $loader = $this->buildLoader($ids);
        return $this->loaders->editableProxy($loader);
    }
    
    protected function pushToMap($ownerId, $preloadedId)
    {
        if(!array_key_exists($ownerId, $this->idMap))
            $this->idMap[$ownerId] = array();
        $this->idMap[$ownerId][] = $preloadedId;
    }
}