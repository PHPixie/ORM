<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Type\One\Side;

class Config extends \PHPixie\ORM\Relationships\Types\Embeds\Side\Config
{
    protected function itemOptionName() {
        return 'item'
    }
    
    protected function defaultOwnerProperty($inflector){
        return $this->itemModel;
    }
}
