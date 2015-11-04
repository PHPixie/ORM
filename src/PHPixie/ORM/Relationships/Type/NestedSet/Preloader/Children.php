<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Preloader;

class Children extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple\IdMap
{
    protected $parentResult;
    protected $rootIds = array();
    
    public function __construct($loaders, $side, $modelConfig, $result, $loader, $parentResult)
    {
        $this->parentResult = $parentResult;
        parent::__construct($loaders, $side, $modelConfig, $result, $loader);
    }
    
    protected function mapItems()
    {
        $sideConfig = $this->side->config();
        
        $idField  = $this->modelConfig->idField;
        $leftKey  = $sideConfig->leftKey;
        $rightKey = $sideConfig->rightKey;
        
        $fields = array($idField, $leftKey, $rightKey);
        
        $childData = $this->result->getFields($fields);
        
        $data = array_merge(
            $this->parentResult->getFields($fields),
            $childData
        );
        
        usort($data, function($a, $b) use($leftKey) {
            return $a[$leftKey] > $b[$leftKey];
        });
        
        $stack = array();
        $currentRight = false;
        $lastId = null;
        
        foreach ($data as $offset => $itemData) {
            if($offset > 0 && $itemData['id'] === $lastId) {
                continue;
            }
            
            while($currentRight !== false && $itemData[$leftKey] > $currentRight) {
                array_pop($stack);
                $currentRight = end($stack);
            }
            
            end($stack);
            $lastId = $itemData[$idField];
            $parentId = key($stack);
            
            if($parentId) {
                $this->pushToMap($parentId, $lastId);
            }else{
                $this->rootIds[]= $lastId;
            }
            
            if($itemData[$rightKey] - $itemData[$leftKey] > 1) {
                $currentRight = $itemData[$rightKey];
                $stack[$lastId] = $currentRight;
            }
        }
    }
}
