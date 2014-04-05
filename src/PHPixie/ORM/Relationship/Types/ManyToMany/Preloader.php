<?php

namespace PHPixie\ORM\Relationship\Types\ManyToMany;

class Preloader
{
    protected $pivotStep;
    
    public function __construct($loaders, $relationshipType, $side, $loader, $pivotStep)
    {
        parent::__construct($loaders, $relationshipType, $side, $loader);
        $this->pivotStep = $pivotStep;
    }
    
    protected function mapItems()
    {
        if ($side == 'right') {
            $ownerIdField = $config->leftPivotKey;
            $itemIdField = $config->rightPivotKey;
        }else {
            $ownerIdField = $config->rightPivotKey;
            $itemIdField = $config->leftPivotKey;
        }
        
        $fields = $this->pivotStep->getFields(array($ownerIdField, $itemIdField));
        
        foreach ($fields as $pivotData) {
            $id = $itemData->$itemIdField;
            $ownerId = $pivotData->$ownerIdField;
            
            if (!isset($this->map[$ownerId]))
                $this->map[$ownerId] = array();
            
            $this->map[$ownerId][] = $id;
        }
        
        $idField = $this->loader->repository()->idField();
        $ids = $this->loader->resultStep->getFields(array($idField));
        foreach($ids as $offset => $id) {
            $this->idOffsets[$id] = $offset;
        }
    }
}