<?php

namespace PHPixie\ORM\Model;

class Data {
    
    protected $data;
    protected $modified = array();
    protected $nested = array();
    protected $cached = array();
    
    public function __construct($data)
    {
        $this->data = (array) $data;
    }
    
    public function get($key)
    {
        $this->prepareProperty($key);
        
        if (array_key_exists($key, $this->modified))
            return $this->modified[$key];
        
        if (array_key_exists($key, $this->nested))
            return $this->nested[$key];
        
        if (array_key_exists($key, $this->data))
            return $this->data[$key];
        
        $args = func_get_args();
        if (array_key_exists(1, $args))
            return $args[1];
        
        throw new \PHPixie\ORM\Exception\Model("Property '$key' not found");
        
    }
    
    public function set($key, $value)
    {
        $value = $this->setDataValue($key, $value);
        $this->modified[$key] = $value;
    }
    
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }
    
    public function asArray()
    {
        $arr = $this->modified;
        
        foreach($this->nested as $key => $nested)
            if(!array_key_exists($key, $arr))
                $arr[$key] = $nested->asArray();
        
        foreach($this->data as $key => $data)
            if(!array_key_exists($key, $arr))
                $arr[$key] = $data;
                
        return $arr;                
    }
}