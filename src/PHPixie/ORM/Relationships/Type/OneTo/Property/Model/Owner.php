<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Property\Model;

class Owner
{
    public function set($owner)
    {
        $this->handler->setOwner($this->side, $this->model, $owner);
    }


}
