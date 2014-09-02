<?php

namespace PHPixie\ORM\Relationships\Relationship\Preloader\Result;

abstract class Multiple extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result
{
    protected function buildLoader($ids)
    {
        return $this->loaders->multiplePreloader($this, $ids);
    }
}