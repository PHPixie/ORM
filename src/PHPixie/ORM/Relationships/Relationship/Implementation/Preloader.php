<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation;

abstract class Preloader implements \PHPixie\ORM\Relationships\Relationship\PreloaderI
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
