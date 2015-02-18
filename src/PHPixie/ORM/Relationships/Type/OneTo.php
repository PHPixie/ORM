<?php

namespace PHPixie\ORM\Relationships\Type;

abstract class OneTo extends \PHPixie\ORM\Relationships\Relationship\Implementation
                 implements \PHPixie\ORM\Relationships\Relationship\Type\Database
{
    abstract public function queryProperty($side, $query);
    abstract public function preloader($side, $modelConfig, $result, $loader);
}
