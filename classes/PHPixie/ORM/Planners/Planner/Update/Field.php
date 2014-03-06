<?php

namespace PHPixie\ORM\Planners\Planner\Update;

class Field
{
    protected $valueSource;
    protected $valueField;

    public function __construct($valueSource, $valueField)
    {
        $this->valueSource = $valueSource;
        $this->valueField = $valueField;
    }

    public function valueSource()
    {
        return $this->valueSource;
    }

    public function valueField()
    {
        return $this->valueField;
    }
}
