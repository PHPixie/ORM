<?php

namespace PHPixie\ORM\Mapper\Group\Optimizer;
use \PHPixie\ORM\Conditions\Condition\Group;

class Merger
{
    protected $orm;

    public function __construct($orm)
    {
        $this->orm = $orm;
    }

    public function findMergeTarget($conditionList, $newCondition, $logicPrecedance)
    {
        if (!($newCondition instanceof Group\Relationship))
            return null;

        $unrelatedSubgroup = null;
        $nextAvailable = true;

        for ($i = count($conditionList) - 1; $i >= 0; $i-- ) {
            $cond = $conditionList[$i];
            $newPrec = $logicPrecedance[$newCondition->logic];
            $condPrec = $i === 0 ? 0 : $logicPrecedance[$cond->logic];

            $isAvailable = $nextAvailable;
            $nextAvailable = $condPrec <= $newPrec;

            if ($condPrec > $newPrec || !$isAvailable)
                continue;

            if ($cond instanceof Group\Relationship) {
                if ($cond->relationship === $newCondition->relationship)
                    return $i;

            } elseif ($unrelatedSubgroup === null && $cond instanceof Group) {
                $unrelatedSubgroup = $i;
                continue;
            }

            if ($newPrec > $condPrec)
                return null;
        }

        return $unrelatedSubgroup;
    }

    public function mergeGroups($left, $right, $logicPrecedance)
    {
        if (($left instanceof Group\Relationship) xor ($right instanceof Group\Relationship))
            return false;

        if ($left instanceof Group\Relationship && $right instanceof Group\Relationship && $right->relationship != $left->relationship)
            return false;

        if ($left->negated() || $right->negated())
            return false;
        foreach($right->conditions() as $key=>$rcond)
            if ($key > 0 && $logicPrecedance[$rcond->logic] < $logicPrecedance[$right->logic])
                return false;
        foreach($right->conditions() as $key => $rcond)
            $left->add($rcond, $key === 0 ? $right->logic : $rcond->logic);

        return true;
    }

}
