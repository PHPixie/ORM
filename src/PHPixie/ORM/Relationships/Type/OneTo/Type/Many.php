<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type;

class Many extends \PHPixie\ORM\Relationships\Type\OneTo
{
    
    public function entityProperty($side, $entity)
    {
        if ($side->type() === 'owner') {
            return $this->ownerEntityProperty($side, $entity);
        }
        
        return $this->itemsEntityProperty($side, $entity);
    }
    
    public function queryProperty($side, $query)
    {
        if ($side->type() === 'owner') {
            return $this->ownerQueryProperty($side, $query);
        }
        
        return $this->itemsQueryProperty($side, $query);
    }
    
    public function preloader($side, $modelConfig, $result, $loader)
    {
        if ($side->type() === 'owner') {
            return $this->ownerPreloader($side, $modelConfig, $result, $loader);
        }
        
        return $this->itemsPreloader($side, $modelConfig, $result, $loader);
    }
    
    public function ownerPreloadValue($propertyName, $owner)
    {
        return new Many\Value\Preload\Owner($propertyName, $owner);
    }
    
    public function ownerPropertyPreloader($owner)
    {
        return new Many\Preloader\Property\Owner($owner);
    }
    
    protected function config($configSlice)
    {
        return new Many\Side\Config($this->configs->inflector(), $configSlice);
    }

    protected function side($type, $config)
    {
        return new Many\Side($type, $config);
    }
    
    protected function sideTypes($config)
    {
        return array('owner', 'items');
    }

    protected function buildHandler()
    {
        return new Many\Handler(
            $this->models,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->mappers,
            $this
        );
    }

    protected function ownerEntityProperty($side, $entity)
    {
        return new Many\Property\Entity\Owner($this->handler(), $side, $entity);
    }
    
    protected function itemsEntityProperty($side, $entity)
    {
        return new Many\Property\Entity\Items($this->handler(), $side, $entity);
    }
    
    protected function ownerQueryProperty($side, $query)
    {
        return new Many\Property\Query\Owner($this->handler(), $side, $query);
    }
    
    protected function itemsQueryProperty($side, $query)
    {
        return new Many\Property\Query\Items($this->handler(), $side, $query);
    }
    
    protected function ownerPreloader($side, $modelConfig, $result, $loader)
    {
        return new Many\Preloader\Owner($side, $modelConfig, $result, $loader);
    }
    
    protected function itemsPreloader($side, $modelConfig, $result, $loader)
    {
        return new Many\Preloader\Items($this->loaders, $side, $modelConfig, $result, $loader);
    }
    
}
