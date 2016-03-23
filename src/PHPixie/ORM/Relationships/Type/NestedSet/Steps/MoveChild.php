<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps;

class MoveChild
{
    protected $repository;
    protected $config;
    protected $resultStep;
    protected $parentId;
    
    public function __construct($repository, $config, $resultStep, $parentId)
    {
        $this->repository = $repository;
        $this->config = $config;
        $this->resultStep = $resultStep;
        $this->parentId = $parentId;
    }
    
    public function execute()
    {
        $data = $this->resultStep->getFields(['id', 'rootId', 'left', 'right', 'depth']);
        if(count($data) !== 2) {
            throw new \Exception("");
        }
        
        if($data[0]['id'] == $this->parentId) {
            $parent = $data[0];
            $child  = $data[1];
        }elseif($data[0]['id'] == $this->parentId) {
            $parent = $data[1];
            $child  = $data[0];
        }else{
            throw new \Exception("");
        }
        
        $childIsNew = $child['rootId'] === null;
        $parentIsNew = $parent['rootId'] === null;
        
        $width = $childIsNew ? 2 : $child['right'] - $child['left'] + 1;
        
        if($parentIsNew) {
            $this->prepareNode($parent['id'], 1, $parent['id'], $width, 0);
            $rootId = $parent['id'];
            $parentLeft = 1;
            $parentRight = 2;
            $parentDepth = 0;
            $childLeft = 2;
        }else{
            $rootId = $parent['rootId'];
            $this->move($width, $parent['right'], $parent['rootId']);
            $parentLeft = $parent['left'];
            $parentRight = $parent['right']+$width;
            $parentDepth = $parent['depth'];
            $childLeft = $parent['right'];
        }

        if($childIsNew) {
            $this->prepareNode($child['id'], $childLeft, $rootId, 0, $parentDepth+1);
        }else{
            if($child['left'] > $parent['right']) {
                $childOffset = $width;
            }else{
                $childOffset = 0;
            }

            $distance = $parent['right'] - $child['left'] - $childOffset;

            $this->updateQuery()
                ->increment('left', $distance)
                ->increment('right', $distance)
                ->set('rootId', $parent['rootId'])
                ->where('left', '>=', $child['left'] + $childOffset)
                ->where('right', '<=', $child['right'] + $childOffset)
                ->where('rootId', $child['rootId'])
                ->execute();
        }

        if(!$parentIsNew) {
            $this->move(-$width, $child['right'], $child['rootId']);
        }
    }
    
    public function move($offset, $right, $rootId)
    {
        foreach(array('left', 'right') as $property) {
            $this->updateQuery()
                ->increment($property, $offset)
                ->where($property, '>=', $right)
                ->where('rootId', $rootId)
                ->execute();
        }
    }
                                   
    public function prepareNode($id, $left, $rootId, $innerWidth, $depth)
    {
        $this->updateQuery()
            ->set('left', $left)
            ->set('right', $left+$innerWidth+1)
            ->set('rootId', $rootId)
            ->set('depth', $depth)
            ->where('id', $id)
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
