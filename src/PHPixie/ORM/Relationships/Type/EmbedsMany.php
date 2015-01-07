<?php

namespace PHPixie\ORM\Relationships\Type;

class EmbedsMany extends \PHPixie\ORM\Relationships\Relationship\Implementation
{

    public function config($config)
    {
        return new Embeds\Type\Many\Side\Config($config);
    }

    public function side($propertyName, $config)
    {
        return new Embeds\Type\Many\Side($this, $propertyName, $config);
    }

    public function buildHandler()
    {
        return new Embeds\Type\Many\Handler($this->ormBuilder, $this, $repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper, $this->embeddedGroupMapper);
    }

    protected function sideTypes($config)
    {
        return $config->properties();
    }

    public function loader($config, $ownerLoader)
    {
        $loaders = $this->ormBuilder->loaders();

        return new Embeds\Type\Many\Loader($loaders, $config, $ownerLoader);
    }

    public function preloader()
    {
        
    }
    
    public function preloadResult()
    {
    
    }

    public function entityProperty($side, $model)
    {
        return new Embeds\Type\Many\Property\Entity\Items($this->handler(), $side, $model);
    }
}
