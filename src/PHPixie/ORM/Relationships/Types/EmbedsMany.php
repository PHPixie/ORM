<?php

namespace PHPixie\ORM\Relationships\Types;

class EmbedsMany extends PHPixie\ORM\Relationships\Types\Embedded\Type\Embeds
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
        $embedsGroupMapper = $this->buildEmbedsGroupMapper();

        return new Embeds\Handler($this->ormBuilder, $this, $repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper, $embedsGroupMapper);
    }

    protected function sideTypes($config)
    {
        return $config->properties();
    }

    protected buildEmbedsGroupMapper()
    {
        $relationshipMap = $this->ormBuilder->relationshipMap();

        return new Embedded\Mapper\Group($this->ormBuilder, $relationshipMap);
    }

    public function arrayLoader($property, $models)
    {
        return new Embeds\Property\Model\EmbeddedArray($this->orm->loaders(), $property, $models);
    }

}
