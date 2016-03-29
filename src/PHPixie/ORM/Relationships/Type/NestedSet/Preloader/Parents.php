<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Preloader;

class Parents extends \PHPixie\ORM\Relationships\Type\NestedSet\Preloader
{
    protected $map = array();

    protected function getMappedFor($entity)
    {
        $entityId = $entity->id();
        if(!array_key_exists($entityId, $this->map)) {
            return null;
        }

        $id = $this->map[$entityId];
        return $this->getEntity($id);
    }

    protected function pushToMap($parentId, $childId)
    {
        $this->map[$childId] = $parentId;
    }
}
