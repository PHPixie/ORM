<?php

namespace PHPixie\ORM\Relationship\Types\Embedded\Type\Embeds\Type\Many;

class Loader extends \PHPixie\ORM\Relationship\Types\Embedded\Type\Embeds\Loader
{
    protected function addPropertyItems($property)
    {
        foreach($property->value() as $item)
            $this->items[] = $item;
    }
}