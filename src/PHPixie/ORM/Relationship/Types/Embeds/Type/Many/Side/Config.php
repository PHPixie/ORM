<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Type\Many\Side;

class Config extends \PHPixie\ORM\Relationships\Types\Embeds\Side\Config
{
    protected function itemOptionName() {
        return 'items'
    }
    
    protected function defaultOwnerProperty($inflector){
        return $inflector->plural($this->itemModel);
    }
}
