<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps;

class MoveChild extends \PHPixie\ORM\Steps\Step
{
    protected $repository;
    protected $config;
    protected $result;
    protected $parentId;
    
    public function __construct($repository, $config, $result, $parentId)
    {
        $this->repository = $repository;
        $this->config = $config;
        $this->result = $result;
        $this->parentId = $parentId;
    }
    
    public function execute()
    {
        $modelConfig = $this->repository->config();
        $config = $this->config;

        $fields = array(
            $idField = $modelConfig->idField,
            $rootIdKey = $config->rootIdKey,
            $leftKey = $config->leftKey,
            $rightKey = $config->rightKey,
            $depthKey = $config->depthKey
        );

        $data = $this->result->getFields($fields);

        if(count($data) !== 2) {
            throw new \PHPixie\ORM\Exception("The result should contain exactly two items");
        }

        if($data[0][$idField] == $this->parentId) {
            $parent = $data[0];
            $child  = $data[1];
        }elseif($data[1][$idField] == $this->parentId) {
            $parent = $data[1];
            $child  = $data[0];
        }else{
            throw new \PHPixie\ORM\Exception("Parent node not found in result");
        }
        
        $childIsNew = $child[$rootIdKey] === null;
        $parentIsNew = $parent[$rootIdKey] === null;

        if(!$parentIsNew && !$childIsNew && $child[$rootIdKey] == $parent[$rootIdKey]) {
            if($child[$leftKey] < $parent[$leftKey] && $child[$rightKey] > $parent[$rightKey]) {
                throw new \PHPixie\ORM\Exception\Relationship("Cannot add parent to its child");
            }

            if($child[$leftKey] > $parent[$leftKey] && $child[$rightKey] < $parent[$rightKey]
                && $child[$depthKey] - 1 == $parent[$depthKey]) {
                return;
            }
        }

        $width = $childIsNew ? 2 : $child[$rightKey] - $child[$leftKey] + 1;

        if($parentIsNew) {
            $this->prepareNode($parent[$idField], 1, $parent[$idField], $width, 0);
            $rootId = $parent[$idField];
            $parentDepth = 0;
            $childLeft = 2;
        }else{
            $rootId = $parent[$rootIdKey];
            $this->move($width, $parent[$rightKey], $parent[$rootIdKey]);
            $parentDepth = $parent[$depthKey];
            $childLeft = $parent[$rightKey];
        }

        if($childIsNew) {
            $this->prepareNode($child[$idField], $childLeft, $rootId, 0, $parentDepth+1);
        }else{
            //If the child was already moved to the right, we need to compensate for it
            $childOffset = ($rootId == $child[$rootIdKey] && $child[$leftKey] > $childLeft) ? $width : 0;

            $distance = $childLeft - $child[$leftKey] - $childOffset;
            
            $this->updateQuery()
                ->increment($leftKey, $distance)
                ->increment($rightKey, $distance)
                ->increment($depthKey, $parentDepth+1 - $child[$depthKey])
                ->set($rootIdKey, $rootId)
                ->where($leftKey, '>=', $child[$leftKey] + $childOffset)
                ->where($rightKey, '<=', $child[$rightKey] + $childOffset)
                ->where($rootIdKey, $child[$rootIdKey])
                ->execute();
        }

        if(!$childIsNew) {
            $this->move(-$width, $child[$rightKey] + $childOffset, $child[$rootIdKey]);
        }
    }
    
    public function move($offset, $right, $rootId)
    {
        $config = $this->config;

        foreach(array($config->leftKey, $config->rightKey) as $property) {
            $this->updateQuery()
                ->increment($property, $offset)
                ->where($property, '>=', $right)
                ->where($config->rootIdKey, $rootId)
                ->execute();
        }
    }
                                   
    public function prepareNode($id, $left, $rootId, $innerWidth, $depth)
    {
        $modelConfig = $this->repository->config();
        $config = $this->config;

        $this->updateQuery()
            ->set($config->leftKey, $left)
            ->set($config->rightKey, $left+$innerWidth+1)
            ->set($config->rootIdKey, $rootId)
            ->set($config->depthKey, $depth)
            ->where($modelConfig->idField, $id)
            ->execute();
    }
    
    public function updateQuery()
    {
        return $this->repository->databaseUpdateQuery();
    }
    
    
    public function usedConnections()
    {
        return array(
            $this->repository->connection()
        );
    }
}
