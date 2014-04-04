<?php

namespace PHPixie\ORM\Relationships\OneTo\Type\One\Preloader;

class Items extends \PHPixie\ORM\Relationship\Type\Preloader\Result\Single
{

    protected function mapItems()
    {
        $idField = $this->loader->repository()->idField();
        $key = $this->side->config()->itemKey;
        
        $fields = $this->loader->resultStep->getFields(array($idField, $key));
        
        foreach ($fields as $offset => $itemData) {
            $id = $itemData->$idField;
            $ownerId = $itemData->$key;
            $this->idOffsets[$id] = $offset;
            $this->map[$ownerId] = $id;
        }
    }
}
