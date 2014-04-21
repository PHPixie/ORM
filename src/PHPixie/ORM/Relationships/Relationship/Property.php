<?php

namespace PHPixie\ORM\Properties;

abstract class Property
{
    protected $handler;
    protected $side;

    public function __construct($handler, $side)
    {
        $this->handler = $handler;
        $this->side = $side;
    }

    abstract protected function propertyOwner();

    abstract public function data($recursive = true);
}
