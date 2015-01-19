<?php

namespace PHPixie\ORM;

class Conditions
{
    public function placeholder($defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($defaultOperator);
        return new \PHPixie\ORM\Conditions\Condition\Collection\Placeholder($container, $allowEmpty);
    }

    public function operator($field, $operator, $values)
    {
        return new \PHPixie\ORM\Conditions\Condition\Field\Operator($field, $operator, $values);
    }

    public function group()
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\Group();
    }

    public function relatedToGroup($relationship)
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\RelatedTo\Group($relationship);
    }

    public function in($items)
    {
        return new \PHPixie\ORM\Conditions\Condition\In($items);
    }
    
    public function container($defaultOperator = '=')
    {
        return new \PHPixie\ORM\Conditions\Builder\Container($this, $defaultOperator);
    }

}
