<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader;

class Item extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Single
{
    protected $map = array();
    
    protected function mapItems()
    {
        $ownerKey = $this->side->config()->ownerKey;
        $idField = $this->loader->repository()->idField();
        $fields = $this->loader->reusableResult()->getFields(array($idField, $ownerKey));
        foreach($fields as $offset => $row) {
            $id = $row[$idField];
            $ownerId = $row[$ownerKey];
            
            $this->idOffsets[$id] = $offset;
            $this->map[$ownerId] = $id;
        }
        
    }

    public function getMappedIdFor($model)
    {
        return $this->map[$model->id()];
    }
}
