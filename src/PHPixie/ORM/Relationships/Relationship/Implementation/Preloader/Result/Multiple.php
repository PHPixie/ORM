<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result;

abstract class Multiple extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result
{
    
    protected $loaders;
    
    public function __construct($loaders, $side, $loader)
    {
        $this->loaders = $loaders;
        parent::__construct($side, $loader);
    }
    
    protected function buildLoader($ids)
    {
        return $this->loaders->multiplePreloader($this, $ids);
    }
}