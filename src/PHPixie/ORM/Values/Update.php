<?php

namespace PHPixie\ORM\Values;

class Update
{
    protected $values;
    protected $query;
    
    protected $updates = array();
    
    public function __construct($values, $query)
    {
        $this->values = $values;
        $this->query  = $query;
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
    
    public function plan()
    {
        return $this->query->planUpdateBuilder($this);
    }
    
    public function execute()
    {
        $this->query->planUpdateBuilder($this)->execute();;
    }
}