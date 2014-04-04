<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Property;

abstract class Model extends \PHPixie\ORM\Relationship\Type\Property\Model
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
