<?php

namespace PHPixie\ORM\Data;

class Diff
{
    protected $set;

    public function __construct($set)
    {
        $this->set = $set;
    }

    public function set()
    {
        return $this->set;
    }
}