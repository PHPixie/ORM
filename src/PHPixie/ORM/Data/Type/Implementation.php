<?php

namespace PHPixie\ORM\Data\Type;

abstract class Implementation implements \PHPixie\ORM\Data\Type
{
    public function set($key, $value)
    {
        $this->setValue($key, $value);
        return $this;
    }
    
    protected abstract function setValue($key, $value);
    public abstract function get($key, $default = null);
    public abstract function data();
}