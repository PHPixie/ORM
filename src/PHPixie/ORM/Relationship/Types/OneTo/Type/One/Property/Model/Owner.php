<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Model;

class Owner extends \PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Model
{

    public function set($owner)
    {
        $this->processSet($owner, $this->model);
    }
}