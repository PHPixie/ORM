<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps\PrepareNode;

class Parent extends \PHPixie\ORM\Relationships\Type\NestedSet\Steps\PrepareNode
{
    protected $result;
    
    public function execute()
    {
        $nodeData = $this->getFields();
        $node = $this->normalizeData($this->config, $nodeData);
        
        $this->updateQuery()
            ->set($this->config->rootIdField, $node['id'])
            ->execute();
        
        $offset = 1 - $nodeData['left'];
        $this->updateQuery()
            ->increment($this->config->leftField, $offset)
            ->increment($this->config->rightField, $offset)
            ->where($this->config->rootIdField, $nodeData['id'])
            ->execute();
    }
    
    protected function normalizeData($nodeData)
    {
        $keys = array('id', 'rootId', 'left', 'right');
        array_combine($keys, $nodeData);
    }
}
