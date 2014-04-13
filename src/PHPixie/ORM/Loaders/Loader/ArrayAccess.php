<?php

namespace PHPixie\ORM\Loaders\Loader;

class ArrayAccess extends \PHPixie\ORM\Loaders\Loader
{
    protected $arrayAccess;
    
    public function __construct($loaders, $arrayAccess)
    {
        $this->property = $property;
        parent::construct($loaders);
    }
    
    public function offsetExists($offset)
    {
        return $this->arrayAccess->offsetExists($offset);
    }
    
    protected function getModelByOffset($offset)
    {
        return $this->property->offsetGet($offset);
    }
}