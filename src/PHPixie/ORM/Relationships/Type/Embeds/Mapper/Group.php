<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Mapper;

class Group
{
    protected $relationships;
    protected $relationshipMap;

    public function __construct($relationships, $relationshipMap)
    {
        $this->relationships = $relationships;
        $this->relationshipMap = $relationshipMap;
    }

    public function mapConditions($builder, $conditions, $modelName, $plan, $fieldPrefix = null)
    {
        foreach ($conditions as $cond) {

            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
                $prefix = $fieldPrefix === null ? '' : $fieldPrefix.'.';
                $builder->addOperatorCondition($cond->logic(), $cond->negated(), $prefix.$cond->field, $cond->operator, $cond->values);

            } elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Collection) {
                throw new \PHPixie\ORM\Exception\Mapper("Embedded relationships do not support collection conditions");

            } elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
                $this->mapRelationshipGroup($builder, $cond, $modelName, $plan, $fieldPrefix);

            } elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group) {
                $this->mapConditionGroup($builder, $cond, $modelName, $plan, $fieldPrefix);

            }else
                throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
        }

    }

    public function mapConditionGroup($builder, $group, $modelName, $plan, $fieldPrefix = null)
    {
        $builder->startGroup($group->logic(), $group->negated());
        $this->mapConditions($builder, $group->conditions(), $modelName, $plan, $fieldPrefix);
        $builder->endGroup();
    }

    protected function mapRelationshipGroup($builder, $group, $modelName, $plan, $fieldPrefix)
    {
        $side = $this->relationshipMap->getSide($modelName, $group->relationship());
        $relationshipType = $side->relationshipType();
        $handler = $this->relationships->get($relationshipType)->handler();

        if (!($handler instanceof \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Handler))
            throw new \PHPixie\ORM\Exception\Mapper("Embedded models can only have embedded reltionships.");

        $handler->mapRelationship($side, $builder, $group, $plan, $fieldPrefix);
    }

}
