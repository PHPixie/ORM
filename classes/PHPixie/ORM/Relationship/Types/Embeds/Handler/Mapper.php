<?php

namespace PHPixe\ORM\Relationships\Embed\Handler;

class Mapper {

    protected function mapConditions($conditions, $query, $embeddedMap, $fieldPrefix = null)
	{
		$fieldPrefix = $embeddedConfig->fieldPrefix();
		
        foreach ($conditions as $cond) {
            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
                $dbQuery->getBuilder('where')->addOperatorCondition($cond->logic, $cond->negated, $fieldPrefix.$cond->field, $cond->operator, $cond->values);
				
            } elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
                $this->mapRelationshipGroup($cond, $query, $embeddedMap, $fieldPrefix);
				
            } elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group) {
                $this->mapConditionGroup($cond, $query, $embeddedMap, $fieldPrefix);

            }else
                throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
        }
    }

    protected function mapConditionGroup($group, $query, $embeddedMap, $fieldPrefix = null)
    {
        $query->startWhereGroup($group->logic, $group->negated());
        $this->mapConditions($group->conditions(), $query, $embeddedMap, $fieldPrefix = null);
        $builder->endWhereGroup();
    }
	
    protected function mapRelationshipGroup($group, $query, $embeddedMap, $fieldPrefix)
    {
		$embeddedConfig = $embeddedMap->propertyConfig($group->relationship());
		if ($fieldPrefix !== null)
			$fieldPrefix.= '.';
		$fieldPrefix.= $embeddedConfig->path().'.';
		
        $this->mapConditionGroup($group, $query, $embeddedConfig->map(), $fieldPrefix);
    }
} 