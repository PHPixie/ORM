<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Property;

abstract class Model extends \PHPixie\ORM\Relationships\Relationship\Property\Model
{
    protected $handler;

    public function query()
    {
        return $this->handler->query($this->side, $this->model);
    }
}
