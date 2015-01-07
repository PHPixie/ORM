<?php

namespace PHPixie\ORM\Mappers\Conditions;

use \PHPixie\ORM\Conditions\Condition\Group;

class Optimizer extends \PHPixie\Database\Conditions\Logic\Parser
{
    protected $conditions;

    public function __construct($conditions)
    {
        $this->conditions = $conditions;
    }
    
    public function optimize($conditions)
    {
        return $this->extractGroups($conditions, true);
    }
    
    protected function normalize($condition)
    {
        return array($condition);
    }
    
    protected function extractGroups($conditions, $parseLogic = false)
    {
        $extracted = array();
        $count = count($conditions);
        
        foreach($conditions as $key => $condition) {
            
            if($condition instanceof Group) {
                $optimized = $this->optimize($condition->conditions());
                $condition->setConditions($optimized);
            }
               
            if(!$this->isConditionGroup($condition) || $condition->negated()) {
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
                if($key == 0)
                    $groupCondition->setLogic($condition->logic());
                $extracted[]= $groupCondition;
            }
        }
        
        if($parseLogic)
            $extracted = $this->parseLogic($extracted);
        
        return $extracted;
    }
    
    protected function isConditionGroup($condition)
    {
        if(!($condition instanceof Group))
            return false;
        
        if($condition instanceof Group\Relationship)
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
        
        if ($rightCondition instanceof Group\Relationship) {
            
            if(($target = $this->findMergeTarget($left, $rightCondition)) !== null) {
                $this->mergeRelationshipGroups($left[$target], $rightCondition);
            
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

            if ($current instanceof Group\Relationship && $this->areMergeable($current, $newCondition)) {
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
        
        if($left->negated() !== $right->negated())
            return false;
        
        if($right->logic() === 'xor')
            return false;
        
        return true;
    }

    protected function mergeRelationshipGroups($left, $right)
    {
        $newLeft = $this->conditions->group();
        $newRight = $this->conditions->group();
        
        $newLeft->setConditions($left->conditions());
        $newRight->setConditions($right->conditions());
        
        $newRight->setLogic($right->logic());
        if($left->negated()) {
            if($right->logic() === 'and') {
                $newRight->setLogic('or');
            }else{
                $newRight->setLogic('and');
            }
        }
            
        $conditions = $this->extractGroups(array($newLeft, $newRight));
        $left->setConditions($conditions);
        
    }

}