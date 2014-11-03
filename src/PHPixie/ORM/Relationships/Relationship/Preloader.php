<?php

namespace PHPixie\ORM\Relationships\Relationship;

abstract class Preloader
{
    protected $loader;

    public function __construct($loader)
    {
        $this->loader = $loader;
    }

    public function loader()
    {
        return $this->loader;
    }

    abstract public function loadProperty($property);
}
