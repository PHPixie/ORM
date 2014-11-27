<?php

namespace PHPixie\ORM\Values;

class Update
{
    protected $data = array();
    
    public function __construct($values)
    {
        
    }
    
    public function increment($field, $amount)
    {
        $this->updates[$field] = $this->values->updateIncrement($amount);
        return $this;
    }
    
    public function decrement($field, $amount)
    {
        return $this->increment($field, -$amount);
    }
    
    public function set($field, $value)
    {
        $this->updates[$field] = $value;
        return $this;
    }
}