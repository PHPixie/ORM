<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many;

class Handler extends \PHPixie\ORM\Relationships\Type\OneTo\Handler
{
    public function loadOwnerProperty($side, $item)
    {
        $owner = parent::loadSingleProperty($side, $item);
        if($owner === null) {
            $this->removeItemOwner($side->config(), $item);
            
        }else {
            $this->addOwnerItems($side->config(), $owner, $item);
        }
    }
    
    public function loadItemsProperty($side, $related)
    {
        $loader = $this->query($side, $related)->findAll();
        
        $preloader = $this->relationshipType->ownerPropertyPrloader($loader, $related);
        $loader->addPreloader($side->config()->itemProperty, $preloader);
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
        $this->processItems('remove', $config, $items);
    }

    public function removeItemOwner($config, $item)
    {
        $this->processItems('remove', $config, $item);
    }

    public function removeAllOwnerItems($config, $owner)
    {
        $property = $this->getLoadedProperty($owner, $config->ownerProperty());
        if($property === null)
            return;

        $loader = $property->value();
        $items = $loader->accessedModels();

        $loader->removeAll();
        $this->processItems('remove', $config, $items, null, false);
    }

    public function resetProperties($side, $items)
    {
        $config = $side->config();
        
        if($side->type() === 'owner') {
            $this->resetOwnerProperties($config, $items);
        }else{
            $this->processItems('reset', $config, $items);
        }
    }
    
    protected function resetOwnerProperties ($config, $owners)
    {
        if(!is_array($owners))
            $owners = array($owners);

        foreach($owners as $owner) {
            $this->processOwner('reset', $config, $owner);

        }

    }

    protected function processItems($action, $config, $items, $owner = null, $processOldOwner = true)
    {
        if(!is_array($items))
            $items = array($items);

        if($action == 'set' && $owner instanceof \PHPixie\ORM\Models\Type\Database\Query)
            $action = 'reset';
            
        foreach($items as $item) {
            if($item instanceof \PHPixie\ORM\Models\Type\Database\Query)
                continue;
            
            $property = $item->getRelationshipProperty($config->itemOwnerProperty, $action !== 'reset');
            
            if($property === null)
                continue;
            
            if($processOldOwner && $property->isLoaded()) {
                $oldOwner = $property->value();
                if($oldOwner !==null ) {

                    if( !($action === 'set' && $owner->id() === $oldOwner->id()) )
                        $this->processOwner('remove', $config, $oldOwner, $item);
                }
            }

            if($action === 'reset') {
                $property->reset();

            }elseif($action === 'set') {
                $property->setValue($owner);

            }else{
                $property->setValue(null);

            }
        }
    }

    protected function processOwner($action, $config, $owner, $items = array())
    {
        if(!is_array($items))
            $items = array($items);
        
        
        if($owner instanceof \PHPixie\ORM\Models\Type\Database\Query)
            return;
        
        
        $property = $this->getLoadedProperty($owner, $config->ownerProperty());
        
        if($property === null)
            return;

        foreach($items as $item) {
            if($item instanceof \PHPixie\ORM\Models\Type\Database\Query) {
                $action = 'reset';
                break;
            }
        }

        $loader = $property->value();

        if($action === 'reset') {
            $property->reset();

        }elseif($action === 'add') {
            $loader->add($items);

        }else{
            $loader->remove($items);

        }

    }
    
    public function unlinkPlan($config, $owners, $items)
    {
        return $this->getUnlinkPlan($config, true, $owners, true, $items);
    }

    public function unlinkItemsPlan($config, $items)
    {
        return $this->getUnlinkPlan($config, false, null, true, $items);
    }

    public function unlinkOwnersPlan($config, $owners)
    {
        return $this->getUnlinkPlan($config, true, $owners, false, null);
    }

}
