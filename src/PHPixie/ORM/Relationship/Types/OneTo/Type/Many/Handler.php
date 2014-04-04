<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Type\Many;

class Handler extends \PHPixie\ORM\Relationships\Types\OneTo\Handler
{
    public function setItemsOwner($config, $items, $newOwner, $restrictOldOwner = null)
    {
        $ownerPropertyName = $config->itemProperty;
        $itemsHaveQueries = false;
        
        foreach($items as $item) {
            if ($item instanceof \PHPixie\ORM\Query) {
                $itemsHaveQueries = true;
                continue;
            }
            
            if (($ownerProperty = $this->getLoadedProperty($item, $config->itemProperty))!==null)
                continue;
                
            $oldOwner = $ownerProperty->value();
            
            if ($restrictOldOwner !== null && $oldOwner->id() !== $newOwner->id())
                continue;
            
            if ($restrictOldOwner === null)
                if(($oldOwnerProperty = $this->getLoadedProperty($oldOwner, $config->ownerProperty))!==null)
                    $oldOwnerProperty->value()->remove(array($item));
            
            $ownerProperty->setValue($newOwner);
        }
        
        $oldItemsProperty = $this->getLoadedProperty($restrictOldOwner, $config->ownerProperty);
        $newItemsProperty = $this->getLoadedProperty($newOwner, $config->ownerProperty);
        
        if ($itemsHaveQueries) {
            if ($oldItemsProperty)
                $oldItemsProperty->reset();
            
            if ($newItemsProperty)
                $newItemsProperty->reset();
        }else {
            if ($oldItemsProperty)
                $oldItemsProperty->value()->remove($items);
            
            if ($newItemsProperty)
                $newItemsProperty->value()->add($items);            
        }
    }
    
    public function resetItemsProperties($config, $items)
    {
        foreach($items as $item) {
            if (!($item instanceof \PHPixie\ORM\Model))
                continue;
            
            if (($property = $this->getLoadedProperty($item, $config->itemProperty)) !== null)
                $property->reset();
        }
    }
    
    public function loadProperty($side, $related)
    {
        if($side === 'owner')
            return  parent::loadProperty($side, $related);
        
        $loader = $this->query($side, $related)->findAll();
        if ($related instanceof \PHPixie\ORM\Model)
            $loader = $this->relationshipType->ownerLoader($loader, $side->config()->itemProperty, $related);
            
        return $this->loaders->editable($loader);
    }
}
