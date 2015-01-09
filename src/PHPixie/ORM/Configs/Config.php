<?php

namespace PHPixie\ORM\Configs;

abstract class Config
{
    public function __construct($inflector, $configSlice)
    {
        $this->processConfig($configSlice, $inflector);
    }

    public function get($key)
    {
        return $this->$key;
    }

    abstract protected function processConfig($configSlice, $inflector);
}
