<?php

namespace PHPixie\ORM;

class Conditions
{
    public function placeholder($defaultOperator = '=', $allowEmpty = true)
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\Placeholder($this, $defaultOperator, $allowEmpty);
    }

    public function operator($field, $operator, $values)
    {
        return new \PHPixie\ORM\Conditions\Condition\Field\Operator($field, $operator, $values);
    }

    public function group()
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\Group();
    }

    public function relationshipGroup($relationship)
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\Group\Relationship($relationship);
    }

    public function in($collectionItems)
    {
        return new \PHPixie\ORM\Conditions\Condition\In($collectionItems);
    }
    
    public function container($defaultOperator = '=')
    {
        return new \PHPixie\ORM\Conditions\Builder\Container($this, $defaultOperator);
    }

}
