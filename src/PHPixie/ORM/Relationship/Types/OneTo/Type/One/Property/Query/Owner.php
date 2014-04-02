<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Query;

class Owner extends \PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Query
{

    public function set($owner)
    {
        $this->processSet($owner, $this->model);
    }
	
}