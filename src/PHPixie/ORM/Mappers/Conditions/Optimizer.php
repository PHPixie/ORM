<?php

namespace PHPixie\ORM\Mappers\Conditions;

use \PHPixie\ORM\Conditions\Condition\Collection;

class Optimizer extends \PHPixie\Database\Conditions\Logic\Parser
{
    protected $conditions;

    public function __construct($mappers, $conditions)
    {
        $this->mappers    = $mappers;
        $this->conditions = $conditions;
    }
    
    public function optimize($conditions)
    {
        return $this->extractCollections($conditions, true);
    }
    
    protected function normalize($condition)
    {
        return array($condition);
    }
    
    protected function extractCollections($conditions, $parseLogic = false)
    {
        $extracted = array();
        $count = count($conditions);
        foreach($conditions as $key => $condition) {
            
            if($condition instanceof \PHPixie\ORM\Conditions\Condition\In) {
                
                $condition = $this->mappers->conditionsNormalizer()->normalizeIn($condition);
                $this->optimizeCollectionConditions($condition);
                
            }elseif($condition instanceof Collection) {
                $condition = $this->cloneCollectionCondition($condition);
                $this->optimizeCollectionConditions($condition);
                
            }
            
            if(!$this->isConditionCollection($condition) || $condition->isNegated()) {
                $extracted[] = $condition;
                continue;
            } 
            
            $minPrecedance = 0;
            if($key > 0) {
                $precedance = $this->logicPrecedance[$condition->logic()];
                $minPrecedance = max($minPrecedance, $precedance);
            }
            
            if($key < $count - 1) {
                $precedance = $this->logicPrecedance[$conditions[$key+1]->logic()];
                $minPrecedance = max($minPrecedance, $precedance);
            }
            
            if(!$this->isExpandable($condition, $minPrecedance)) {
                $extracted[] = $condition;
                continue;
            }
            
            $parseLogic = true;
            
            foreach($condition->conditions() as $key => $groupCondition) {
                if($key == 0) {
                    $groupCondition->setLogic($condition->logic());
                }
                $extracted[]= $groupCondition;
            }
        }
        
        if($parseLogic) {
            $extracted = $this->parseLogic($extracted);
            if($extracted === null) {
                $extracted = array ( );
            }
        }
        
        return $extracted;
    }
    
    protected function isConditionCollection($condition)
    {
        if(!($condition instanceof Collection))
            return false;
        
        if($condition instanceof Collection\RelatedTo)
            return false;
        
        return true;
    }
    
    protected function isExpandable($group, $minPrecedance)
    {
        $conditions = $group->conditions();
        foreach($conditions as $key => $condition){
            if($key == 0)
                continue;
            
            if($this->logicPrecedance[$condition->logic()] < $minPrecedance)
                return false;
        }
        
        return true;
    }
    
    protected function merge($left, $right)
    {
        if(count($right) !== 1)
            return $this->mergeConditions($left, $right);
        
        $rightCondition = current($right);
        
        if ($rightCondition instanceof Collection\RelatedTo) {
            
            if(($target = $this->findMergeTarget($left, $rightCondition)) !== null) {
                $this->mergeRelatedToCollections($left[$target], $rightCondition);
                
            
            }else{
                $left[]= $rightCondition;
            }
            
        }else{
            $left[]= $rightCondition;
        }
        
        return $left;
    }
    
    protected function mergeConditions($left, $right)
    {
        foreach($right as $condition) {
            $left[]= $condition;
        }
        
        return $left;
    }
    
    protected function findMergeTarget($conditionList, $newCondition)
    {
        $isNextFree = true;
        $newPrecedance = $this->logicPrecedance[$newCondition->logic()];
        
        for ($i = count($conditionList) - 1; $i >= 0; $i-- ) {
            $current = $conditionList[$i];
            
            $currentPrecedance = 0;
            if($i > 0) {
                $currentPrecedance = $this->logicPrecedance[$current->logic()];
            }
            
            $isCurrentFree = $isNextFree;
            
            $isNextFree = $currentPrecedance <= $newPrecedance;

            if (!$isNextFree || !$isCurrentFree)
                continue;

            if ($current instanceof Collection\RelatedTo && $this->areMergeable($current, $newCondition)) {
                return $i;
            }

            if ($newPrecedance > $currentPrecedance)
                return null;
        }

        return null;
    }
    
    protected function areMergeable($left, $right)
    {
        
        if ($left->relationship() !== $right->relationship())
            return false;
        
        if($left->isNegated() xor $right->isNegated())
            return false;
        
        if($right->logic() === 'or' && !$left->isNegated())
            return true;
        
        if($right->logic() === 'and' && $left->isNegated())
            return true;
        
        return false;
    }

    protected function mergeRelatedToCollections($left, $right)
    {
        $newLeft = $this->conditions->group();
        $newRight = $this->conditions->group();
        
        $newLeft->setConditions($left->conditions());
        $newRight->setConditions($right->conditions());
        
        $newRight->setLogic($right->logic());
        if($left->isNegated()) {
            $newRight->setLogic('or');
        }
        
        $conditions = $this->extractCollections(array($newLeft, $newRight));
        $left->setConditions($conditions);
    }
    
    protected function cloneCollectionCondition($condition)
    {
        if($condition instanceof Collection\RelatedTo) {
            $group = $this->conditions->relatedToGroup($condition->relationship());
        }else{
            $group = $this->conditions->group();
        }

        $group->setLogic($condition->logic());
        $group->setIsNegated($condition->isNegated());
        $group->setConditions($condition->conditions());
        
        return $group;
    }
    
    protected function optimizeCollectionConditions($collection)
    {
        $optimized = $this->optimize($collection->conditions());
        $collection->setConditions($optimized);
    }
    
}