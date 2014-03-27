<?php

namespace PHPixie\ORM\Loaders;

class Preloader extends \PHPixie\ORM\Loader
{
    protected $preloader;
    protected $ids;
    
    public function __construct($orm, $preloader, $ids, $preloaders)
    {
        parent::__construct($orm, $preloaders);
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