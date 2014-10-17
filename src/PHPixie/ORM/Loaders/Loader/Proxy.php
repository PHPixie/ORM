<?php

namespace PHPixie\ORM\Loaders\Loader;

abstract class Proxy extends \PHPixie\ORM\Loaders\Loader
{
    protected $loader;
    
    public function __construct($loaders, $loader)
    {
        parent::__construct($loaders);
        $this->loader = $loader;
    }
    
    public function loader()
    {
        return $this->loader;
    }
}