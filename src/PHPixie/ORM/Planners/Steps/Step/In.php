<?php

namespace PHPixie\ORM\Query\Plan\Step;

class In
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
        $values = $this->resultStep->result()->getColumn($resultField);
        $placeholder->where($this->placeholderField, 'in', $values);
    }
}
