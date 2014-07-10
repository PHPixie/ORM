<?php

namespace PHPixie\ORM\Data;

interface Type
{
    public function set($key, $value);
    public function get($key);
    public function data();
}