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
        return new \PHPixie\ORM\Conditions\Condition\Placeholder($field, $operator, $values);
    }

    public function group()
    {
        return new \PHPixie\ORM\Conditions\Condition\Group();
    }

    public function relationshipGroup($relationship)
    {
        return $this->orm->relationshipGroup($relationship);
    }

    public function collection($collectionItems)
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection($collectionItems);
    }

}
