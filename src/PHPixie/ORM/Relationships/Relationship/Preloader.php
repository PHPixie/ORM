<?php

namespace PHPixie\ORM\Relationships\Relationship;

abstract class Preloader
{
    protected $loaders;
    protected $relationship;
    protected $side;
    protected $loader;

    public function __construct($loaders, $relationship, $side, $loader)
    {
        $this->loaders             = $loaders;
        $this->relationshipType    = $relationship;
        $this->side                = $side;
        $this->loader              = $loader;
    }

    public function loader()
    {
        return $this->loader;
    }

    abstract public function valueFor($model);
}
