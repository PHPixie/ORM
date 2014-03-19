<?php

namespace PHPixie\ORM\Driver\Mongo\Repository\Embedded\Conditions;

class Builder extends \PHPixie\DB\Conditions\Builder
{
    protected $fieldPrefix;
    
    public function __construct($conditions, $fieldPrefix, $defaultOperator = '=')
    {
        $this->fieldPrefix = $fieldPrefix;
        parent::__construct($conditions, $defaultOperator);
    }
    
    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        parent::addOperatorCondition($logic, $negate, $this->fieldPrefix.'.'.$field, $operator, $values);
    }
}