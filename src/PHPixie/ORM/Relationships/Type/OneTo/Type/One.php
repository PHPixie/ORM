<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type;

class One extends \PHPixie\ORM\Relationships\Type\OneTo
{
    
    public function entityProperty($side, $entity)
    {
        return new One\Property\Entity($this->handler(), $side, $entity);
    }
    
    public function queryProperty($side, $query)
    {
        return new One\Property\Query($this->handler(), $side, $query);
    }
    
    public function preloader($side, $modelConfig, $result, $loader)
    {
        if ($side->type() === 'owner') {
            return $this->ownerPreloader($side, $modelConfig, $result, $loader);
        }
        
        return $this->itemPreloader($side, $modelConfig, $result, $loader);
    }
    
    protected function config($configSlice)
    {
        return new One\Side\Config($this->configs->inflector(), $configSlice);
    }

    protected function side($type, $config)
    {
        return new One\Side($type, $config);
    }
    
    protected function sideTypes($config)
    {
        return array('owner', 'item');
    }

    protected function buildHandler()
    {
        return new One\Handler(
            $this->models,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->mappers,
            $this
        );
    }
    
    protected function ownerPreloader($side, $modelConfig, $result, $loader)
    {
        return new One\Preloader\Owner($side, $modelConfig, $result, $loader);
    }
    
    protected function itemPreloader($side, $modelConfig, $result, $loader)
    {
        return new One\Preloader\Item($side, $modelConfig, $result, $loader);
    }
    
}
