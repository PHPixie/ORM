<?php

namespace PHPixie\ORM\Model;

abstract class Preloader
{
    protected $loaders;
    protected $side;
    protected $loader;

    public function __construct($loaders, $side, $loader)
    {
        $this->loaders    = $loaders;
        $this->side       = $side;
        $this->loader     = $loader;
    }
    
    public function loader()
    {
        return $this->loader();
    }
    
    public abstract function loadFor($model);
}