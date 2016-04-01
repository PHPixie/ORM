<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Property;

class Query extends \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Query
{
    protected $handler;

    public function query()
    {
        return $this->handler->query($this->side, $this->query);
    }
}
