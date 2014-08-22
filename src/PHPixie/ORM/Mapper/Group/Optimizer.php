<?php

namespace PHPixie\ORM\Mapper\Group;
use \PHPixie\ORM\Conditions\Condition\Group;

class Optimizer extends \PHPixie\DB\Conditions\Logic\Parser
{
    protected $orm;
    protected $merger;

    public function __construct($orm, $merger)
    {
        $this->orm = $orm;
        $this->merger = $merger;
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
        if (!($group instanceof Group\Relationship) && count($conditions) === 1) {
            $subgroup = current($conditions);
            if ($group->negated())
                $subgroup->negate();
            $subgroup->logic = $group->logic;
            $group = $subgroup;
        } else {
            $group->setConditions($conditions);
        }

        return $group;
    }

    protected function merge($left, $right)
    {
        if (count($right) !== 1) {
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
            $sameRelationship = $cond->relationship === $right->relationship;

        $optimizeMerge = true;

        $isSuitable = $cond instanceof Group && !$sameRelationship;
        $isSuitable = $isSuitable || ($cond instanceof Group\Relationship && $sameRelationship);

        if (!$isSuitable) {
            $group = $this->orm->conditionGroup();

            $group->logic = $cond->logic;
            $group->add($cond,  'and');
            $left[$target] = $cond = $group;
        } elseif ($cond->negated()) {
            $group = $this->orm->conditionGroup();
            $group->logic = 'and';
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

    public function optimize($group)
    {
        //print_r($group); die;
        reset($group);

        return $this->expandGroup($group);
    }

}
