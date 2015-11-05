<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany;

class Handler extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
                    implements \PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Database,
                               \PHPixie\ORM\Relationships\Relationship\Handler\Preloading
{
    public function linkPlan($config, $parent, $child)
    {
        $plan = $this->plans->steps();
        $repository = $this->repository($config);
        
        $ids = array();
        foreach($nodes as $node) {
            $ids[]= $node->id();
        }
        
        $query = $repository->databaseSelectQuery();
        $query->addInOperatorCondition(
            $repository->config()->idField,
            $ids
        );

        $resultStep = $this->nestedSetSteps->iteratorResult($query);
        $plan->addStep($resultStep);
        
        $moveStep = $this->planner->moveNode($config, $result, $parent->id());
        $plan->addStep($moveStep);
        
        
        
        $parentIsNew = $parent['rootId'] === null
        if($parentIsNew) {
            $this->prepareNode($parent['id'], 1, $parent['id'], $width);
            $rootId = $parent['id'];
        }else{
            $rootId = $parent['rootId'];
            $this->move($width, $parent['right'], $parent['rootId']);
        }
        
        if($child['rootId'] === null) {
            $this->prepareNode($child['id'], $parent['left'], $parent['rootId'], 0);
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
                ->where('rootId', $child['rootId']);
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
                ->where('rootId', $rootId);
        }
    }
                                   
    public function prepareRoot($id, $left, $rootId, $innerWidth)
    {
        $this->updateQuery()
            ->set('left', $left)
            ->set('right', $left+$innerWidth+1)
            ->set('rootId', $rootId)
            ->where('id', $id);
    }
    
    protected function repository($config)
    {
        return $this->models->database()->repository($config->model);
    }
}
