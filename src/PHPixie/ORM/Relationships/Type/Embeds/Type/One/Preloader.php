<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\One;

class Preloader extends \PHPixie\ORM\Relationships\Type\Embeds\Preloader
{
    protected function getEntities($property)
    {
        $value = $property->value();
        if($value === null) {
            return array();
        }
        
        return array($value);
    }
}