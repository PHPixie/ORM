<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side;

class Config extends \PHPixie\ORM\Relationships\Type\OneTo\Side\Config
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
