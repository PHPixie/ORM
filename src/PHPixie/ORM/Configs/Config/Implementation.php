<?php

namespace PHPixie\ORM\Configs\Config;

abstract class Implementation
{
    public function get($key)
    {
        return $this->$key;
    }
}
