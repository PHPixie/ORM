<?php

namespace PHPixie\ORM\Relationships\Relationship\Property;

abstract class Implementation
{
    protected $handler;
    protected $side;

    public function __construct($handler, $side)
    {
        $this->handler = $handler;
        $this->side = $side;
    }

}
