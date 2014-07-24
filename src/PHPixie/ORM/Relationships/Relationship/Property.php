<?php

namespace PHPixie\ORM\Relationships\Relationship;

abstract class Property
{
    protected $handler;
    protected $side;

    public function __construct($handler, $side)
    {
        $this->handler = $handler;
        $this->side = $side;
    }

    abstract public function data($recursive = true);
}
