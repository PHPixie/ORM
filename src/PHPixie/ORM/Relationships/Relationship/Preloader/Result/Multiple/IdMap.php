<?php

namespace PHPixie\ORM\Relationships\Relationship\Preloader\Result\Multiple;

abstract class IdMap extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Multiple
{
    protected $idMap = array();

    protected function getMappedFor($model)
    {
        $ids = $this->idMap[$model->id()];
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