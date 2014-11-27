<?php

namespace PHPixie\ORM\Values\Update;

class Increment
{
    protected $amount;
    
    public function __construct($amount)
    {
        $this->amount = $amount;
    }
    
    public function amount()
    {
        return $this->amount;
    }
}