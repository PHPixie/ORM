<?php

namespace PHPixie\ORM\Steps\Step;

class In extends \PHPixie\ORM\Steps\Step
{
    protected $placeholderContainer;
    protected $placeholderField;
    protected $resultStep;
    protected $resultField;

    public function __construct($placeholderContainer, $placeholderField, $resultStep, $resultField)
    {
        $this->placeholderContainer = $placeholderContainer;
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
