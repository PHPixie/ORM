<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader;

class Item extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Single
{
    protected $map = array();
    
    protected function mapItems()
    {
        $ownerKey = $this->side->config()->ownerKey;
        $idField = $this->modelConfig->idField;
        
        $fields = $this->result->getFields(array($idField, $ownerKey));
        foreach($fields as $offset => $row) {
            $id = $row[$idField];
            $ownerId = $row[$ownerKey];
            
            $this->idOffsets[$id] = $offset;
            $this->map[$ownerId] = $id;
        }
    }

    protected function getMappedIdFor($entity)
    {
        $id = $entity->id();
        
        if(!array_key_exists($id, $this->map)) {
            return null;
        }
        
        return $this->map[$id];
    }
}
