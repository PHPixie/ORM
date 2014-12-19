<?php

namespace PHPixie\ORM\Models;

class Model
{
    protected $wrappers;
    
    public function __construct($wrappers)
    {
        $this->wrappers = $wrappers;
    }
    
    public abstract function enity($modelName, $data);
    
    public function config()
    {
        if(!array_key_exists($modelName, $this->configs)) {
            $configSlice = $this->models->configSlice($modelName, $this->type());
            $this->configs[$modelName] = $this->buildSlice($modelName, $configSlice);
        }
        
        return $this->configs[$modelName];
    }
}