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
        
        if ($rightCondition instanceof Group\Relationship) {
            
            if(($target = $this->findMergeTarget($left, $rightCondition)) !== null) {
                $this->mergeRelationshipGroups($left[$target], $rightCondition);
                $left[$target] = $this->optimizeGroup($left[$target]);
            }else{
                $left[]= $rightCondition;
            }
            
        }elseif($rightCondition instanceof Group && $this->isExtractable($rightCondition)) {
            $left = $this->extractGroup($left, $rightCondition);
            $left = $this->optimize($left);
            
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
    
    protected function isExtractable($group)
    {
        if($group->negated())
            return false;
        
        $precedance = $this->logicPrecedance[$group->logic()];
        foreach($group->conditions() as $condition) {
            if($this->logicPrecedance[$condition->logic()] < $precedance)
                return false;
        }
        
        return true;
    }
    
    protected function extractGroup($left, $group)
    {
        $conditions = $group->conditions();
        if(count($conditions) > 0) {
            $conditions[0]->setLogic($group->logic());
        }
        
        return $this->mergeConditions($left, $conditions);
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
        if($this->isExtractable($right)) {
            $conditions = $left->conditions();
            $conditions = $this->extractGroup($conditions, $right);
            
        }else{
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
            
            if($this->isExtractable($newRight)) {
                $conditions = $newLeft->conditions();
                $conditions = $this->extractGroup($conditions, $newRight);
            }else{
                $conditions = array($newLeft, $newRight);
            }
        }
        $left->setConditions($conditions);
    }

}
