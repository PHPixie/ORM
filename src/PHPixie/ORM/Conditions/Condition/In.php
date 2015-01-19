<?php

namespace PHPixie\ORM\Conditions\Condition;

class In extends \PHPixie\ORM\Conditions\Condition\Implementation
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
