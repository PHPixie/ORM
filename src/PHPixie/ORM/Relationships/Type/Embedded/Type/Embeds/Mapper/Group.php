<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Mapper;

class Group
{
    protected $relationships;
    protected $relationshipMap;

    public function __construct($relationships, $relationshipMap)
    {
        $this->ormBuilder = $relationships;
        $this->relationshipMap = $relationshipMap;
    }

    public function mapConditions($builder, $conditions, $modelName, $plan, $fieldPrefix = null)
    {
        foreach ($conditions as $cond) {

            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
                $prefix = $fieldPrefix === null ? '' : $fieldPrefix.'.';
                $builder->addOperatorCondition($cond->logic, $cond->negated(), $prefix.$cond->field, $cond->operator, $cond->values);

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

    public function mapConditionGroup($group, $builder, $modelName, $plan, $fieldPrefix)
    {
        $query->startGroup($group->logic, $group->negated());
        $this->mapConditions($builder, $group->conditions(), $modelName, $plan, $fieldPrefix);
        $builder->endGroup();
    }

    protected function mapRelationshipGroup($group, $builder, $modelName, $plan, $fieldPrefix)
    {
        $side = $this->relationshipMap->getSide($modelName, $group->relationship);
        $handler = $this->relationships->relationshipType($side-> relationshipType())->handler();

        if (!($handler instanceof \PHPixie\ORM\Relationships\Type\Embedded\Type\Embedsded\Handler))
            throw new \PHPixie\ORM\Exception\Mapper("Embedded models can only hve embedded reltionships.");

        $handler->mapRelationship($side, $query, $group, $plan, $fieldPrefix);
    }

}
