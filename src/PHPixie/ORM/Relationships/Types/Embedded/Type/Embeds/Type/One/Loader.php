<?php

namespace PHPixie\ORM\Relationship\Types\Embedded\Type\Embeds\Type\One;

class Loader extends \PHPixie\ORM\Relationship\Types\Embedded\Type\Embeds\Loader
{
    protected function addPropertyItems($property)
    {
        $item = $property->value();
        if($item !== null)
            $this->items[] = $item;
    }
}