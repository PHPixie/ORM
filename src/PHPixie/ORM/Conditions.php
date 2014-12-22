<?php

namespace PHPixie\ORM;

class Conditions
{
    public function placeholder($defaultOperator = '=', $allowEmpty = true)
    {
        return new \PHPixie\ORM\Conditions\Condition\Placeholder($this, $defaultOperator, $allowEmpty);
    }

    public function operator($field, $operator, $values)
    {
        return new \PHPixie\ORM\Conditions\Condition\Operator($field, $operator, $values);
    }

    public function group()
    {
        return new \PHPixie\ORM\Conditions\Condition\Group();
    }

    public function relationshipGroup($relationship)
    {
        return new \PHPixie\ORM\Conditions\Condition\Group\Relationship($relationship);
    }

    public function collection($collectionItems)
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection($collectionItems);
    }
    
    public function container($defaultOperator = '=')
    {
        return new \PHPixie\ORM\Conditions\Builder\Container($this, $defaultOperator);
    }

}
