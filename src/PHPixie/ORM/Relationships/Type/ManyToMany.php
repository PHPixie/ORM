<?php

namespace PHPixie\ORM\Relationships\Type;

class ManyToMany extends \PHPixie\ORM\Relationships\Relationship\Implementation
                 implements \PHPixie\ORM\Relationships\Relationship\Type\Database
{
    public function entityProperty($side, $entity)
    {
        return new ManyToMany\Property\Entity($this->handler(), $side, $entity);
    }
    
    public function queryProperty($side, $query)
    {
        return new ManyToMany\Property\Query($this->handler(), $side, $query);
    }
    
    public function preloader($side, $modelConfig, $result, $loader, $pivotResult)
    {
        return new ManyToMany\Preloader($this->loaders, $side, $modelConfig, $result, $loader, $pivotResult);
    }
    
    protected function config($configSlice)
    {
        return new ManyToMany\Side\Config($this->configs->inflector(), $configSlice);
    }

    protected function side($type, $config)
    {
        return new ManyToMany\Side($type, $config);
    }

    protected function sideTypes($config)
    {
        return array('left', 'right');
    }
    
    protected function buildHandler()
    {
        return new ManyToMany\Handler(
            $this->models,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->mappers,
            $this
        );
    }
}
