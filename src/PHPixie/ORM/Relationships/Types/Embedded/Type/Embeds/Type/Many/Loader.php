<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type\Embeds\Type\Many;

class Loader extends \PHPixie\ORM\Relationships\Types\Embedded\Type\Embeds\Loader
{
    protected function addPropertyItems($property)
    {
        foreach($property->value() as $item)
            $this->items[] = $item;
    }
}
