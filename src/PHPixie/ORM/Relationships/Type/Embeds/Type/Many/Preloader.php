<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\Many;

class Preloader extends \PHPixie\ORM\Relationships\Type\Embeds\Preloader
{
    protected function getEntities($property)
    {
        return $property->value()->asArray();
    }
}