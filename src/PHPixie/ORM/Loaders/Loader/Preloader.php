<?php

namespace PHPixie\ORM\Loaders\Loader;

class Preloader extends \PHPixie\ORM\Loaders\Loader
{
    protected $preloader;
    protected $ids;
    
    public function __construct($loaders, $preloader, $ids, $preloaders)
    {
        parent::__construct($loaders, $preloaders);
        $this->preloader = $preloader;
        $this->ids = $ids;
    }
    
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->ids);
    }
    
    public function getByOffset($offset)
    {
        return $this->preloader->getModel($this->ids[$offset]);
    }
}