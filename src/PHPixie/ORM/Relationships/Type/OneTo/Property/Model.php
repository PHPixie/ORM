<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Property;

abstract class Model extends \PHPixie\ORM\Relationships\Relationship\Property\Model
{
    protected $handler;

    public function query()
    {
        return $this->handler->query($this->side, $this->model);
    }

    public function load()
    {
        return $this->handler->loadProperty($this->side, $this->model);
    }
}
