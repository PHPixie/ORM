<?php

namespace PHPixie\ORM\Values\Updates\Update;

class Implementation
{
    protected $arrays = array();
    protected $aliases = array();
    
    public function set($values)
    {
        return $this->setArrayKeyValues('set', func_get_args());
    }
    
    public function clearSet()
    {
        return $this->clearArray('set');
    }
    
    public function execute()
    {
        $this->plan()->execute();
        return $this->query;
    }
    
    public function plan()
    {
        return $this->queryMapper->mapUpdate($this->query, $this);
    }
    
    protected function setArrayValues($name, $values)
    {
        if(is_array($values)) {
            foreach($values as $value) {
                $this->sets[$name][]= $value;
            }
            
        }else{
            $this->sets[$name][]= $values;
        }
        
        return $this;
    }
    
    protected function setArrayKeyValues($name, $args)
    {
        if(count($args) == 1) {
            foreach($args[0] as $key => $value) {
                $this->sets[$name][$key] = $value;
            }
            
        }else{
            $this->sets[$name][$args[0]] = $args[1];
        }
        
        return $this;
    }
    
    public function __call($method, $args) {
        $method = $this->aliases[$method];
        return call_user_func_array(array($this, $method), $args);
    }
}