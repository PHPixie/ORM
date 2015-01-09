<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type;

class Many extends \PHPixie\ORM\Relationships\Type\Embeds
{
    public function entityProperty($side, $entity)
    {
        return new Many\Property\Entity\Items($this->handler(), $side, $entity);
    }
    
    public function preloader()
    {
        return new Many\Preloader();
    }
    
    public function preloadResult($reusableResult, $embeddedPrefix)
    {
        return new Many\Preload\Result($reusableResult, $embeddedPrefix);
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
        return array('items');
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

}
