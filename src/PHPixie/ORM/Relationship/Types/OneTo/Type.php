<?php

namespace PHPixie\ORM\Relationships\Types\OneTo;

class Type extends PHPixie\ORM\Relationship\Type
{
    public function preloader($side, $loader)
    {
        $class = __NAMESPACE__.ucfirst($side->type());
        return new $class($this->orm->loaders(), $side, $loader)
    }
}
