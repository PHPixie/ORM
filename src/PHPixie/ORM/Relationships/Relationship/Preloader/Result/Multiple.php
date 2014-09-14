<?php

namespace PHPixie\ORM\Relationships\Relationship\Preloader\Result;

abstract class Multiple extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result
{
    
    public function __construct($loaders, $side, $loader)
    {
        parent::__construct($side, $loader);
    }
    
    protected function buildLoader($ids)
    {
        return $this->loaders->multiplePreloader($this, $ids);
    }
}