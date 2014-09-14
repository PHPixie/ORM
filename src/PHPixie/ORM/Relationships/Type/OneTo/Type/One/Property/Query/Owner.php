<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Query;

class Owner extends \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Query
{

    public function set($owner)
    {
        $this->processSet($owner, $this->model);
    }

}
