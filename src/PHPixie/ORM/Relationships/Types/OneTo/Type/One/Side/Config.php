<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Type\One\Side;

class Config extends \PHPixie\ORM\Relationships\Types\OneTo\Side\Config
{
    protected function itemOptionName()
    {
        return 'item'
    }

    protected function defaultOwnerProperty($inflector)
    {
        return $this->itemModel;
    }
}
