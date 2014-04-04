<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Property;

abstract class Query extends \PHPixie\ORM\Relationship\Type\Property\Query
{
    protected $handler;
    
    public function query()
    {
        return $this->handler->query($this->side, $this->query);
    }
}
