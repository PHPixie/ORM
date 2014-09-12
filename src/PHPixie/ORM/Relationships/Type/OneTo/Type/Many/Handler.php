<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many;

class Handler extends \PHPixie\ORM\Relationships\Type\OneTo\Handler
{
    public function loadProperty($side, $related)
    {
        if($side === 'owner')

            return  parent::loadProperty($side, $related);

        $loader = $this->query($side, $related)->findAll();
        if ($related instanceof \PHPixie\ORM\Model)
            $loader = $this->relationshipType->ownerLoader($loader, $side->config()->itemProperty, $related);

        return $this->loaders->editable($loader);
    }
    
    public function addOwnerItems($config, $owner, $items)
    {
        $this->processOwner('add', $config, $owner, $items);
        $this->processItems('set', $config, $items, $owner);
    }
    
    public function removeOwnerItems($config, $owner, $items)
    {
        $this->processOwner('remove', $config, $owner, $items);
        $this->processItems('remove', $config, $items, $owner);
    }
    
    public function removeAllOwnerItems($config, $owner)
    {
        $this->processAllOwnerItems('remove', $config, $owner);
    }
    
    public function resetProperties($side, $items)
    {
        if($side->type() === 'owner') {
            $this->processOwner('reset', $config, $items);
        }else{
            $this->processItems('reset', $config, $items);
        }
        
        foreach($
    }
    
    public function removeItemOwner($config, $item)
    {
        $this->processItems('remove', $config, $item);
    }
        
    public function resetItemsProperties($config, $items)
    {
        $this->processItems('reset', $config, $items);
    }
    
    protected function processAllOwnerItems($action, $owner)
    {
        $property = $this->getLoadedProperty($owner, $config->ownerProperty);
        if($property === null)
            return;
        
        $loader = $property->value();
        $items = $loader->usedModels();
        
        if($action === 'remove') {
            $loader->removeAll();
        }else {
            $property->reset();
        }
        
        $this->processItems($action, $config, $items, null, false);
    }
    
    protected function processOwner($action, $config, $owner, $items)
    {
        if(!is_array($items))
            $items = array($items);
        
        if($owner instanceof \PHPixie\ORM\Query)
            return;
        
        $property = $this->getLoadedProperty($owner, $config->ownerProperty);
        if($property === null)
            return;
        
        foreach($items as $item) {
            if($item instanceof \PHPixie\ORM\Query) {
                $property->reset();
                return;
            }
        }
        
        $loader = $property->value();
        
        if($action === 'add') {
            $property->add($items);
        }else{
            $property->remove($items);
        }
        
    }
    
    protected function processItems($action, $config, $items, $owner = null, $processOldOwner = true)
    {
        if(!is_array($items))
            $items = array($items);
        
        if ($owner !== null && $owner instanceof \PHPixie\ORM\Query)
            $action = 'reset';
        
        foreach($items as $item) {
            if($item instanceof \PHPixie\ORM\Query)
                continue;
            
            $property = $item->relationshipProperty($config->itemProperty);
            
            $oldOwner = null;
            
            if($pocessOldOwner && $property->isLoaded()) {
                $oldOwner = $property->value();
                if($oldOwner && $oldOwner === $owner){
                    $this->processOldOwner($action, $config, $oldOwner, $owner);
                }
            }
            
            if($action === 'reset') {
                $property->reset();
                
            }elseif($action == 'set') {
                $property->setValue($owner);
                
            }elseif($action = 'remove') {
                $propety->setValue(null);
                
            }
            
        }
    }
    
    protected function processOldOwner($itemAction, $config, $oldOwner, $owner)
    {
        $remove = true;
        
        if($owner !== null) {
            
            $sameId = $oldOwner->id() === $owner->id();
        
            $remove = $itemAction === 'set' && !$sameId;
            $remove = $remove || ( $itemAction === 'remove' && $sameId );
        }
        
        if($remove)
            $this->processOwner('remove', $config, $oldOwner, $item);
    }
    
}
