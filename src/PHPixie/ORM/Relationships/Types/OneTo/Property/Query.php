<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Property;

abstract class Query extends \PHPixie\ORM\Relationships\Relationship\Property\Query
{
    protected $handler;

    public function query()
    {
        return $this->handler->query($this->side, $this->query);
    }
}
