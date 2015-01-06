<?php

namespace PHPixie\ORM\Relationships\Type;

class OneToMany extends \PHPixie\ORM\Relationships\Relationship
{
    public function config($config)
    {
        return new OneTo\Type\Many\Side\Config($config);
    }

    public function side($type, $config)
    {
        return new OneTo\Type\Many\Side\Config($type, $config);
    }

    public function buildHandler($repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper)
    {
        return new OneTo\Type\Many\Handler($this->ormBuilder, $this, $repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper);
    }

    public function preloader($side, $loader)
    {
        if ($side->type() === 'owner')
            return new OneTo\Type\Many\Preloader\Owner($this->orm->loaders(), $this, $side, $loader);

        return new OneTo\Type\Many\Preloader\Items($this->orm->loaders(), $this, $side, $loader);
    }

    public function modelProperty($side, $model)
    {
        if ($side->type() === 'owner')
            return new OneTo\Type\Many\Property\Entity\Owner($this->handler(), $side, $model);

        return new OneTo\Type\Many\Property\Entity\Items($this->handler(), $side, $model);
    }

    public function queryProperty($side, $query)
    {
        if ($side->type() === 'owner')
            return new OneTo\Type\Many\Property\Query\Owner($this->handler(), $side, $query);

        return new OneTo\Type\Many\Property\Query\Items($this->handler(), $side, $query);
    }

    public function ownerPropertyPreloader($owner)
    {
        return new OneTo\Type\Many\Preloader\Property\Owner($owner);
    }
    
    public function ownerPreloadValue($owner)
    {
        return new OneTo\Type\Many\Value\Preload\Owner($owner);
    }

    protected function sideTypes($config)
    {
        return array('owner', 'items');
    }

}
