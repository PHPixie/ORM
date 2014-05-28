<?php

namespace PHPixie\ORM\Loaders\Loader;

class Caching extends \PHPixie\ORM\Loaders\Loader
{
    protected $loader;
    protected $models = array();
    
    public function __construct($loader)
    {
        $this->loader = $loader;
    }
    
    public function getByOffset($offset)
    {
        if(!array_key_exists($offset, $this->models))
            $this->models[$offset] = $this->loader->getByOffset($offset);
        
        return $this->models[$offset];
    }
    
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->models) || $this->loader->offsetExists($offset);
    }
}