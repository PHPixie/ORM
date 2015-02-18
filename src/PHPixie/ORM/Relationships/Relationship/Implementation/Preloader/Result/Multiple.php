<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result;

abstract class Multiple extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result
{
    protected $loaders;
    
    public function __construct($loaders, $side, $modelConfig, $result, $loader)
    {
        $this->loaders = $loaders;
        parent::__construct($side, $modelConfig, $result, $loader);
    }
    
    protected function buildLoader($ids)
    {
        return $this->loaders->multiplePreloader($this, $ids);
    }
}