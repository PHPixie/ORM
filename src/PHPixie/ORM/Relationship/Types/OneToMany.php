<?php

namespace PHPixie\ORM\Relationships\Types;

class OneToMany extends PHPixie\ORM\Relationship\Type
{
    public function config($config)
    {
        return new OneTo\Type\Many\Side\Config($config);
    }

    public function side($type, $config)
    {
        return new OneTo\Type\Many\Side\Config($type, $config);
    }

    public function buildHandler()
    {
        return new OneTo\Type\Many\Handler();
    }

    public function preloader($side, $loader)
    {
        if ($side->type() === 'owner')
            return new OneTo\Type\Many\Preloader\Owner($this->orm->loaders(), $side, $loader);
        
        return new OneTo\Type\Many\Preloader\Items($this->orm->loaders(), $side, $loader);
    }
    
    public function modelProperty($side, $model)
    {
        if ($side->type() === 'owner')
            return new OneTo\Type\Many\Property\Model\Owner($this->handler(), $side, $model);
        
        return new OneTo\Type\Many\Property\Model\Items($this->handler(), $side, $model);
    }
    
    public function queryProperty($side, $query)
    {
        if ($side->type() === 'owner')
            return new OneTo\Type\Many\Property\Query\Owner($this->handler(), $side, $query);
        
        return new OneTo\Type\Many\Property\Query\Items($this->handler(), $side, $query);
    }
    
    protected function sideTypes($config)
    {
        return array('owner', 'items');
    }

}
