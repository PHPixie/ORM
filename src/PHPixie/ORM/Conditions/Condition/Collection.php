<?php

namespace PHPixie\ORM\Conditions\Condition;

class Collection extends \PHPixie\ORM\Conditions\Condition
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }
    
    public function items()
    {
        return $this->items;
    }
}
