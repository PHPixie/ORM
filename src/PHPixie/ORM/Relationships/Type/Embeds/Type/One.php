<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type;

class One extends \PHPixie\ORM\Relationships\Type\Embeds
{
    public function entityProperty($side, $entity)
    {
        return new One\Property\Entity\Item($this->handler(), $side, $entity);
    }
    
    public function preloader()
    {
        return new One\Preloader();
    }
    
    public function preloadResult($reusableResult, $embeddedPrefix)
    {
        return new One\Preload\Result($reusableResult, $embeddedPrefix);
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
        return array('item');
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
    
}
