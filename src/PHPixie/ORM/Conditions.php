<?php

namespace PHPixie\ORM;

class Conditions
{
    protected $maps;
    
    public function __construct($maps)
    {
        $this->maps = $maps;
    }
    
    public function placeholder($modelName, $defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($modelName, $defaultOperator);
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

    public function in($modelName, $items)
    {
        return new \PHPixie\ORM\Conditions\Condition\In($modelName, $items);
    }
    
    public function container($modelName, $defaultOperator = '=')
    {
        return new \PHPixie\ORM\Conditions\Builder\Container(
            $this,
            $this->maps->relationship(),
            $modelName,
            $defaultOperator
        );
    }

}
