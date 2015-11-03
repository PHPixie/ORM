<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Preloader;

class Children extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple\IdMap
{
    protected function mapItems()
    {
        $sideConfig = $this->side->config();
        
        $idField  = $this->modelConfig->idField;
        $leftKey  = $sideConfig->leftKey;
        $rightKey = $sideConfig->rightKey;
        
        $fields = $this->result->getFields(array($idField, $leftKey, $rightKey));
        
        $stack = array();
        $currentRight = false;
        
        foreach ($fields as $offset => $itemData) {
            while($currentRight !== false && $itemData[$leftKey] > $currentRight) {
                array_pop($stack);
                $currentRight = end($stack);
            }
            
            end($stack);
            $this->pushToMap(key($stack), $itemData[$idField]);
            
            if($itemData[$rightKey] - $itemData[$leftKey] > 1) {
                $currentRight = $itemData[$rightKey];
                $stack[$itemData[$idField]] = $currentRight;
            }
        }
        
        print_r($this->idMap);die;
    }
}
