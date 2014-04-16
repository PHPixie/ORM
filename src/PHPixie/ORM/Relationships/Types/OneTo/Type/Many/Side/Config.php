<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Type\Many\Side;

class Config extends \PHPixie\ORM\Relationships\Types\OneTo\Side\Config
{
    protected function itemOptionName() {
        return 'items'
    }
    
    protected function defaultOwnerProperty($inflector){
        return $inflector->plural($this->itemModel);
    }
}
