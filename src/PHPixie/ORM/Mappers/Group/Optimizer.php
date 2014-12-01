<?php

namespace PHPixie\ORM\Mappers\Group;

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
        return $this->parseLogic($conditions);
    }
    
    protected function normalize($condition)
    {
        if ($condition instanceof Group) {
            $condition = $this->optimizeGroup($condition);
        }

        return array($condition);
    }

    protected function optimizeGroup($group)
    {
        $conditions = $group->conditions();
        $conditions = $this->optimize($conditions);
        
        $condition = $group;
        
        if (!($group instanceof Group\Relationship) && count($conditions) === 1) {
            $condition = current($conditions);
            if ($group->negated())
                $condition->negate();
            $condition->setLogic($group->logic());
        } else {
            
            $group->setConditions($conditions);
        }

        return $condition;
    }

    protected function merge($left, $right)
    {
        if(count($right) !== 1)
            return $this->mergeConditions($left, $right);
        
        $rightCondition = current($right);
        $target = $this->findMergeTarget($left, $rightCondition);
        
        if($target === null)
            return $this->mergeConditions($left, $right);
        
        $this->mergeRelationshipGroups($left[$target], $rightCondition);
        $left[$target] = $this->optimizeGroup($left[$target]);
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
        if (!($newCondition instanceof Group\Relationship))
            return null;

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
        
        $left->setConditions(array($newLeft, $newRight));
    }

}
