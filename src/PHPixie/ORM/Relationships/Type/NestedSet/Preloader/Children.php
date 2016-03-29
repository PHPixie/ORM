<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Preloader;

class Children extends \PHPixie\ORM\Relationships\Type\NestedSet\Preloader
{
    protected $map = array();

    protected function getMappedFor($entity)
    {
        $entityId = $entity->id();
        if(array_key_exists($entityId, $this->map)) {
            $ids = $this->map[$entityId];
        }else{
            $ids = array();
        }

        $loader = $this->buildLoader($ids);
        return $this->loaders->editableProxy($loader);
    }

    protected function pushToMap($parentId, $childId)
    {
        if(!array_key_exists($parentId, $this->map)) {
            $this->map[$parentId] = array();
        }
        $this->map[$parentId][] = $childId;
    }

    protected function buildLoader($ids)
    {
        return $this->loaders->multiplePreloader($this, $ids);
    }
}
