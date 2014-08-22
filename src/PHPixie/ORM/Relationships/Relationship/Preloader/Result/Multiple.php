<?php

namespace PHPixie\ORM\Relationships\Relationship\Preloader\Result;

abstract class Multiple extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result
{
    protected $map = array();

    protected function getMappedFor($model)
    {
        $ids = $this->map[$model->id()];
        $loader = $this->buildLoader($ids);
        return $this->loaders->editableProxy($loader);
    }

    protected function buildLoader($ids)
    {
        return $this->loaders->multiplePreloader($this, $ids);
    }
}