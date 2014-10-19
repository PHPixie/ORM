<?php

namespace PHPixie\ORM\Conditions\Condition\Group;

protected $items;

class Collection extends \PHPixie\ORM\Conditions\Condition\Group
{
    public function __construct($items)
    {
        $this->items = $items;
    }
    
    public function items()
    {
        return $this->items;
    }
}
