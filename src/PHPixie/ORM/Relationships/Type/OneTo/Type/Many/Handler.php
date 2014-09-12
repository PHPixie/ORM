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
        $this->processItems('remove', $config, $items);
    }

    public function removeItemOwner($config, $item)
    {
        $this->processItems('remove', $config, $item);
    }

    public function removeAllOwnerItems($config, $owner)
    {
        $property = $this->getLoadedProperty($owner, $config->ownerProperty);
        if($property === null)
            return;

        $loader = $property->value();
        $items = $loader->usedModels();

        $loader->removeAll();
        $this->processItems($action, $config, $items, null, false);
    }

    public function resetProperties($side, $items)
    {
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
            $this->processOwner('reset', $config, $owner)

        }

    }

    protected function processItems($action, $config, $items, $owner = null)
    {
        if(!is_array($items))
            $items = array($items);

        if($action == 'set' && $owner instanceof \PHPixie\ORM\Query)
            $action = 'reset';

        $property = $item->relationshipProperty($config->itemProperty);

        foreach($items as $item) {
            if($item instanceof \PHPixie\ORM\Query)
                continue;

            if($property->isLoaded()) {
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

        if($owner instanceof \PHPixie\ORM\Query)
            return;

        $property = $this->getLoadedProperty($owner, $config->ownerProperty);
        if($property === null)
            return;

        foreach($items as $item) {
            if($item instanceof \PHPixie\ORM\Query) {
                $action = 'reset'
                return;
            }
        }

        $loader = $property->value();

        if($action === 'reset') {
            $property->reset();

        if($action === 'add') {
            $loader->add($items);

        }else{
            $loader->remove($items);

        }

    }

}
