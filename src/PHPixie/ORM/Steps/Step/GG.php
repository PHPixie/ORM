<?php

namespace PHPixie\ORM\Steps\Step;

class GG extends \PHPixie\ORM\Steps\Step
{
    protected $result;
    protected $query;

    public function __construct($result, $placeholderField, $resultStep, $resultField)
    {
        $this->result = $result;
        $this->placeholderField = $placeholderField;
        $this->resultStep   = $resultStep;
        $this->resultField  = $resultField;
    }

    public function execute()
    {
        $values = $this->resultStep->getField($this->resultField);
        $this->placeholderContainer->addInOperatorCondition($this->placeholderField, $values);
    }
}
