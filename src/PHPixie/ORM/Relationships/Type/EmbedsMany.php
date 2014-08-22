<?php

namespace PHPixie\ORM\Relationships\Type;

class EmbedsMany extends PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds
{

    public function config($config)
    {
        return new Embedded\Type\Embeds\Type\Many\Side\Config($config);
    }

    public function side($propertyName, $config)
    {
        return new Embedded\Type\Embeds\Type\Many\Side($this, $propertyName, $config);
    }

    public function buildHandler($repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper)
    {
        return new Embedded\Type\Embeds\Type\Many\Handler($this->ormBuilder, $this, $repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper, $this->embeddedGroupMapper);
    }

    protected function sideTypes($config)
    {
        return $config->properties();
    }

    public function loader($config, $ownerLoader)
    {
        $loaders = $this->ormBuilder->loaders();

        return new Embedded\Type\Embeds\Type\Many\Loader($loaders, $config, $ownerLoader);
    }

    public function preloader($side, $loader)
    {
        $loaders = $this->ormBuilder->loaders();

        return new Embedded\Type\Embeds\Type\Many\Loader($loaders, $this, $side, $loader);
    }

    public function modelProperty($side, $model)
    {
        return new Embedded\Type\Embeds\Type\Many\Property\Model\Items($this->handler(), $side, $model);
    }
}
