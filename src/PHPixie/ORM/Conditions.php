<?php

namespace PHPixie\ORM;

class Conditions
{
    protected $ormBuilder;
    
    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
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

    public function in($modelName, $items = array())
    {
        return new \PHPixie\ORM\Conditions\Condition\In($modelName, $items);
    }
    
    public function subquery($field, $subquery, $subqueryField)
    {
        return new \PHPixie\ORM\Conditions\Condition\Field\Subquery($field, $subquery, $subqueryField);
    }
    
    public function container($modelName, $defaultOperator = '=')
    {
        return new \PHPixie\ORM\Conditions\Builder\Container(
            $this,
            $this->ormBuilder->maps()->relationship(),
            $modelName,
            $defaultOperator
        );
    }

}
