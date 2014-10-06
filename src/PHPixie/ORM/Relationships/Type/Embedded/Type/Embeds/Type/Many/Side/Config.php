<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Side;

class Config extends \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Side\Config
{
    public $ownerItemsProperty;
    
    protected function ownerPropertyName()
    {
        return 'ownerItemsProperty';
    }
    
    protected function itemOptionName()
    {
        return 'items';
    }

    protected function defaultOwnerProperty($inflector)
    {
        return $inflector->plural($this->itemModel);
    }
}
