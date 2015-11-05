<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps\PrepareNode;

class Parent extends \PHPixie\ORM\Relationships\Type\NestedSet\Steps\PrepareNode
{
    protected $result;
    
    public function execute()
    {
        $parentData = $this->result->parentData();
        $childData  = $this->result->childData();
        
        if($parentData['rootId'] !== null) {
            $this->move(
                $this->width($childData),
                $parentData[$this->config->rightField],
                $parentData[$this->config->rootIdField]
            );
        }else{
            $this->prepareNode($parentData);
        }
    }
}
