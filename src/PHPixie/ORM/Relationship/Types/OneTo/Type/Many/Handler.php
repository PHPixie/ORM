<?php

namespace PHPixe\ORM\Relationships\Types\OneTo\Type\Many;

class Handler extends \PHPixe\ORM\Relationships\Types\OneTo\Handler
{
    public function setItemsPropertiesOwner($config, $items, $newOwner)
    {
        $ownerPropertyName = $config->itemProperty;
        $resetNewOwner = false;
        
        foreach($items as $item) {
            if ($item instanceof \PHPixie\ORM\Query) {
                $resetNewOwner = true;
                continue;
            }
            
            $ownerProperty = $item->relationshipProperty($config->itemProperty, false);
            
            if ($ownerProperty === null)
                continue;
            
            if (!$ownerProperty->loaded() || $ownerProperty->value() === $newOwner)
                continue;
            
            $resetNewOwner = true;
            $this->resetOwnerProperty($config, $ownerProperty->value());
            $ownerProperty->setValue($newOwner);
        }
        
        if ($resetNewOwner && $newOwner !== null)
            $this->resetOwnerProperty($config, $newOwner);
    }
    
    public function resetOwnerProperty($config, $owner)
    {
        $itemsProperty = $owner->relationshipProperty($config->ownerProperty, false);
        if($itemsProperty!==null)
            $itemsProperty->reset();
    }
    
    public function resetItemsProperties($config, $items)
    {
        foreach($items as $item) {
            if (!($item instanceof \PHPixie\ORM\Model))
                continue;
            
            $ownerProperty = $item->relationshipProperty($config->itemProperty, false);
            if($ownerProperty !== null)
                $ownerProperty->reset();
        }
    }
}
