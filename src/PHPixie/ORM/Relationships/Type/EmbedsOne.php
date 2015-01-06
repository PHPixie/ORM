<?php

namespace PHPixie\ORM\Relationships\Type;

class EmbedsOne extends \PHPixie\ORM\Relationships\Relationship\Implementation
{

    public function config($config)
    {
        return new Embeds\Type\One\Side\Config($config);
    }

    public function side($propertyName, $config)
    {
        return new Embeds\Type\One\Side($this, $propertyName, $config);
    }

    public function buildHandler()
    {
        return new Embeds\Type\One\Handler($this->ormBuilder, $this, $repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper, $this->embeddedGroupMapper);
    }
    public function entityProperty($a, $b){}

    protected function sideTypes($config)
    {
        return $config->properties();
    }

    public function loader($config, $ownerLoader)
    {
        $loaders = $this->ormBuilder->loaders();

        return new Embeds\Type\One\Loader($loaders, $config, $ownerLoader);
    }

    public function preloader()
    {
        
    }
    
    public function preloadResult()
    {
    
    }
    
    public function entityProperty($side, $model)
    {
        return new Embeds\Type\One\Property\Model\Items($this->handler(), $side, $model);
    }
    
}
