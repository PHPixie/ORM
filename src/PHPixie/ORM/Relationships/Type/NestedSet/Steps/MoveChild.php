<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps;

class MoveChild extends \PHPixie\ORM\Relationships\Type\NestedSet\Steps\PrepareNode
{
    protected $result;
    
    public function execute()
    {
        $data = $data->result->asArray();
        if(count($data) !== 2) {
            throw new \Exception("");
        }
        
        if($data[0]['id'] === $this->parentId) {
            $parentData = $data[0];
            $childData  = $data[1];
        }elseif($data[0]['id'] === $this->parentId) {
            $parentData = $data[1];
            $childData  = $data[0];
        }else{
            throw new \Exception("");
        }
        
        
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
        
        $child = $this->result();
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
}
