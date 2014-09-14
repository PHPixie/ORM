<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side;

class Config extends \PHPixie\ORM\Relationships\Type\OneTo\Side\Config
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
