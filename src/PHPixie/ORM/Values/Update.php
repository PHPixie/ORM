<?php

namespace PHPixie\ORM\Values;

class Update
{
    protected $values;
    
    protected $updates = array();
    
    public function __construct($values)
    {
        $this->values = $values;
    }
    
    public function set($key, $value)
    {
        $this->updates[$key] = $value;
        return $this;
    }
    
    public function increment($key, $amount)
    {
        return $this->set($key, $this->values->updateIncrement($amount));
    }
    
    public function remove($key)
    {
        return $this->set($key, $this->values->updateRemove());
    }
    
    public function updates()
    {
        return $this->updates;
    }
}