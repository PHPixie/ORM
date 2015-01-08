<?php

namespace PHPixie\ORM\Relationships\Type\OneTo;

abstract class Relationship extends \PHPixie\ORM\Relationships\Relationship\Implementation
                            implements \PHPixie\ORM\Relationships\Relationship\Type\Database
{
    public function config($config)
    {
        return new ManyToMany\Side\Config($config);
    }

    public function side($type, $config)
    {
        return new ManyToMany\Side($this, $type, $config);
    }

    public function buildHandler()
    {
        return new ManyToMany\Handler($this->ormBuilder, $this, $repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper);
    }

    public function preloader($side, $loader)
    {
        return new ManyToMany\Preloader($this->orm->loaders(), $this, $side, $loader);
    }

    public function entityProperty($side, $model)
    {
        return new ManyToMany\Property\Entity($this->handler(), $side, $model);
    }

    public function queryProperty($side, $model)
    {
        return new ManyToMany\Property\Query($this->handler(), $side, $model);
    }

    protected function sideTypes($config)
    {
        return array('left', 'right');
    }
}
