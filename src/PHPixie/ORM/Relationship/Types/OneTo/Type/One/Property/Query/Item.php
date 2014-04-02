<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Query;

class Item extends \PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Query
{

    public function set($item)
    {
        $this->processSet($this->model, $item);
    }
}