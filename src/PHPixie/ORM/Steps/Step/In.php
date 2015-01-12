<?php

namespace PHPixie\ORM\Steps\Step;

class In extends \PHPixie\ORM\Steps\Step
{
    protected $builder;
    protected $builderField;
    protected $resultStep;
    protected $resultField;

    public function __construct($builder, $builderField, $resultStep, $resultField)
    {
        $this->builder      = $builder;
        $this->builderField = $builderField;
        $this->resultStep   = $resultStep;
        $this->resultField  = $resultField;
    }

    public function execute()
    {
        $values = $this->resultStep->getField($this->resultField);
        $this->builder->_and($this->placeholderField, 'in', $values);
    }
}
