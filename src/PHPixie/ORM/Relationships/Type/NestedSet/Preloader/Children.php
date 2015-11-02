<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Preloader;

class Children extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Multiple\IdMap
{
    protected $idEntities = array();
    
    protected function mapItems()
    {
        foreach($this->loader as $offset => $entity)
        {
            $this->idEntities[$entity->id()] = $offset;
        }
        
        $sideConfig = $this->side->config();
        
        $idField  = $this->modelConfig->idField;
        $leftKey  = $sideConfig->left;
        $rightKey = $sideConfig->right;
        
        $fields = $this->result->getFields(array($idField, $leftKey, $rightKey));
        
        $idStack    = array();
        $rightStack = array();
        
        $currentId    = null;
        $currentRight = null;
        foreach ($fields as $offset => $itemData) {
            while($currentRight !== null && $itemData[$leftKey] > $currentRight) {
                $currentId    = array_pop($idStack);
                $currentRight = array_pop($rightStack);
            }
            
            if($itemData[$rightKey] - $itemData[$leftKey] > 1) {
                $idStack[]= $itemData[$idField];
                $rightStack[]= $itemData[$rightKey];
            }
            
            $this->pushToMap($currentId, $itemData[$idField]);
            $this->idOffsets[$itemData[$idField], $offset]
        }
    }
    
    public function getEntity($id)
    {
        if(array_key_exists($id, $this->idEntities)) {
            return $this->idEntities[$id];
        }
        
        return parent::getEntity($id);
    }
}
