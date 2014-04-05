<?php

namespace PHPixie\ORM\Relationships\Types;

class ManyToMany extends PHPixie\ORM\Relationship\Type
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
        return new ManyToMany\Handler();
    }

    public function preloader($side, $loader)
    {
        return new ManyToMany\Preloader($this->orm->loaders(), $this, $side, $loader);
    }
    
    public function modelProperty($side, $model)
    {
        return new ManyToMany\Property\Model($this->handler(), $side, $model);
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
