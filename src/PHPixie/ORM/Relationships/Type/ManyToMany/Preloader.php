<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany;

class Preloader extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple\IdMap
{
    protected $pivotResult;

    public function __construct($loaders, $side, $modelConfig, $result, $loader, $pivotResult)
    {
        $this->pivotResult = $pivotResult;
        parent::__construct($loaders, $side, $modelConfig, $result, $loader);
    }

    protected function mapItems()
    {
        $type = $this->side->type();
        $opposing = $type === 'left' ? 'right' : 'left';
        
        $config = $this->side->config();
        
        $ownerIdField = $config->get($opposing.'PivotKey');
        $itemIdField = $config->get($type.'PivotKey');
        
        $fields = $this->pivotResult->getFields(array($ownerIdField, $itemIdField));
        $itemIds = $this->result->getField($this->modelConfig->idField);
        $itemIds = array_fill_keys($itemIds, true);
        
        foreach ($fields as $pivotData) {
            $id = $pivotData[$itemIdField];
            $ownerId = $pivotData[$ownerIdField];
            if(isset($itemIds[$id])) {
                $this->pushToMap($ownerId, $id);
            }
        }

        $this->mapIdOffsets();
    }
}
