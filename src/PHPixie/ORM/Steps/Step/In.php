<?php

namespace PHPixie\ORM\Steps\Step;

class In extends \PHPixie\ORM\Steps\Step
{
    protected $placeholder;
    protected $placeholderField;
    protected $resultStep;
    protected $resultField;

    public function __construct($placeholder, $placeholderField, $resultStep, $resultField)
    {
        $this->placeholder = $placeholder;
        $this->placeholderField = $placeholderField;
        $this->resultStep = $resultStep;
        $this->resultField = $resultField;
    }

    public function execute()
    {
        $values = $this->resultStep->getField($this->resultField);
        $this->placeholder->where($this->placeholderField, 'in', $values);
    }
}
