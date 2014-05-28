<?php

namespace PHPixie\ORM\Loaders\Loader;

abstract class Proxy extends \PHPixie\ORM\Loaders\Loader
{
    protected $loader;
    
    public function __construct($loader)
    {
        $this->loader = $loader;
    }
}