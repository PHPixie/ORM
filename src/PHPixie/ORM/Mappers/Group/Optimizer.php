<?php

namespace PHPixie\ORM\Mappers\Group;

use \PHPixie\ORM\Conditions\Condition\Group;

class Optimizer extends \PHPixie\Database\Conditions\Logic\Parser
{
    protected $conditions;
    protected $merger;

    public function __construct($conditions, $merger)
    {
        $this->conditions = $conditions;
        $this->merger = $merger;
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
        if (true || count($right) !== 1) {
            foreach($right as $cond)
                $left[] = $cond;

            return $left;
        }

        $right = current($right);
        $target = $this->merger->findMergeTarget($left, $right, $this->logicPrecedance);
        if ($target === null) {
            $left[] = $right;

            return $left;
        }

        $cond = $left[$target];

        $sameRelationship = false;

        if ($cond instanceof Group\Relationship && $right instanceof Group\Relationship)
            $sameRelationship = $cond->relationship() === $right->relationship();

        $optimizeMerge = true;

        $isSuitable = $cond instanceof Group && !$sameRelationship;
        $isSuitable = $isSuitable || ($cond instanceof Group\Relationship && $sameRelationship);

        if (!$isSuitable) {
            $group = $this->conditions->group();

            $group->setLogic($cond->logic());
            $group->add($cond,  'and');
            $left[$target] = $cond = $group;
        } elseif ($cond->negated()) {
            $group = $this->conditions->group();
            $group->setLogic('and');
            $group->setConditions($cond->conditions());
            $group->negate();
            $cond->negate();
            $cond->setConditions(array($group));
        }

        if (!($right instanceof Group) || !$this->merger->mergeGroups($cond, $right, $this->logicPrecedance)) {

        }

        if ($optimizeMerge) {
            $left[$target] = $cond = $this->optimizeGroup($cond);
        }

        if (count($left) === 1) {
            if (!$cond->negated() && !($cond instanceof Group\Relationship)) {
                return $cond->conditions();
            }
        }

        return $left;

    }



}
