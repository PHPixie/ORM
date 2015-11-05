<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany;

class Handler extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
                    implements \PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Database,
                               \PHPixie\ORM\Relationships\Relationship\Handler\Preloading
{
    public function linkPlan($config, $parent, $child)
    {
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
}
