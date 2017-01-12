<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Property;

abstract class Entity extends    \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity
                      implements \PHPixie\ORM\Relationships\Relationship\Property\Entity\Query,
                                 \PHPixie\ORM\Relationships\Relationship\Property\Entity\Data
{
    protected $handler;

    public function query()
    {
        return $this->handler->query($this->side, $this->entity);
    }

    abstract public function asData($recursive = false);
}
