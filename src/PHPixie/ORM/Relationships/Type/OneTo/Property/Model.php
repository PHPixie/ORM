<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Property;

abstract class Model extends \PHPixie\ORM\Relationships\Relationship\Property\Model
                    implements \PHPixie\ORM\Relationships\Relationship\Property\Model\Data,
                                \PHPixie\ORM\Relationships\Relationship\Property\Model\Query
{
    protected $handler;

    public function query()
    {
        return $this->handler->query($this->side, $this->model);
    }

    abstract public function asData($recursive = false);
}
