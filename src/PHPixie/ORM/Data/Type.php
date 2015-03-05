<?php

namespace PHPixie\ORM\Data;

interface Type
{
    public function set($key, $value);
    public function get($key, $default = null);
    public function getRequired($key);
    public function data();
}