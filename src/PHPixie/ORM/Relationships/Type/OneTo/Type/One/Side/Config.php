<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side;

class Config extends \PHPixie\ORM\Relationships\Type\OneTo\Side\Config
{
    public $ownerItemProperty;
    
    protected function ownerPropertyName()
    {
        return 'ownerItemProperty';
    }
    
    protected function itemOptionName()
    {
        return 'item';
    }

    protected function defaultOwnerProperty($inflector)
    {
        return $this->itemModel;
    }
}
