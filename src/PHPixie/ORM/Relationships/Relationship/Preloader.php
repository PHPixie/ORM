<?php

namespace PHPixie\ORM\Model;

abstract class Preloader
{
    protected $loaders;
    protected $relationshipType;
    protected $side;
    protected $config;
    protected $loader;

    public function __construct($loaders, $relationshipType, $side, $loader)
    {
        $this->loaders             = $loaders;
        $this->relationshipType    = $relationshipType;
        $this->side                = $side;
        $this->config              = $side->config();
        $this->loader              = $loader;
    }

    public function loader()
    {
        return $this->loader();
    }

    abstract public function loadProperty($property);
}
