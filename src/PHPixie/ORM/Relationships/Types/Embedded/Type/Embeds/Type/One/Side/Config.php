<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type\Embeds\Type\One\Side;

class Config extends \PHPixie\ORM\Relationships\Types\Embedded\Type\Embeds\Side\Config
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
