<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation;

abstract class Property implements \PHPixie\ORM\Relationships\Relationship\Property
{
    protected $handler;
    protected $side;

    public function __construct($handler, $side)
    {
        $this->handler = $handler;
        $this->side = $side;
    }

}
