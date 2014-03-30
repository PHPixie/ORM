<?php

namespace PHPixie\ORM\Relationships\Types;

class Embedded extends PHPixie\ORM\Relationship\Type
{
    public function config($config)
    {
        return new Embedded\Side\Config($config);
    }

    public function side($propertyName, $config)
    {
        return new Embedded\Side($this, $propertyName, $config);
    }

    public function buildHandler()
    {
        return new Embedded\Handler();
    }

    protected function sideTypes($config)
    {
        return $config->properties();
    }
    
    public function arrayLoader($property, $models)
    {
        return new Embeds\Property\Model\EmbeddedArray($this->orm->loaders(), $property, $models);
    }

}
