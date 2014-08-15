<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Mapper;

class Group
{
    protected $ormBuilder;
    protected $relationshipMap;

    public function __construct($ormBuilder, $relationshipMap)
    {
        $this->ormBuilder = $ormBuilder;
        $this->relationshipMap = $relationshipMap;
    }

    protected function mapConditions($dbQuery, $conditions, $modelName, $plan, $fieldPrefix = null)
    {

        foreach ($conditions as $cond) {

            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
                $prefix = $fieldPrefix === null ? '' : $fieldPrefix.'.';
                $dbQuery->getWhereBuilder()->addOperatorCondition($cond->logic, $cond->negated, $prefix.$cond->field, $cond->operator, $cond->values);

            } elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Collection) {
                throw new \PHPixie\ORM\Exception\Mapper("Embedded relationships do ot support collection conditions");

            } elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
                $this->mapRelationshipGroup($group, $currentModel, $query, $plan, $fieldPrefix);

            } elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group) {
                $this->mapConditionGroup($cond, $query, $currentModel, $plan, $fieldPrefix);

            }else
                throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
        }
    }

    protected function mapConditionGroup($group, $query, $modelName, $plan, $fieldPrefix)
    {
        $query->startWhereGroup($group->logic, $group->negated());
        $this->mapConditions($query, $group->conditions(), $modelName, $plan, $fieldPrefix);
        $builder->endWhereGroup();
    }

    protected function mapRelationshipGroup($group, $query, $modelName, $plan, $fieldPrefix)
    {
        $side = $this->relationshipMap->getSide($modelName, $group->relationship);
        $handler = $this->ormBuilder->relationshipType($side-> relationshipType())->handler();

        if (!($handler instanceof \PHPixie\ORM\Relationships\Type\Embedded\Type\Embedsded\Handler))
            throw new \PHPixie\ORM\Exception\Mapper("Embedded models can only hve embedded reltionships.");

        $handler->mapRelationship($side, $query, $group, $plan, $fieldPrefix);
    }

}
