<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One;

class Handler extends \PHPixie\ORM\Relationships\Type\OneTo\Handler
{
    public function loadProperty($side, $related)
    {
        $value = parent::loadSingleProperty($side, $related);
        
        if($value === null) {
            $this->unlinkProperties($side, $related);
            
        }elseif($side->type() === 'owner') {
            $this->linkProperties($side->config(), $value, $related);
            
        }else{
            $this->linkProperties($side->config(), $related, $value);
        }
    }
    
    public function linkPlan($config, $owner, $item)
    {
        $unlinkPlan = $this->getUnlinkPlan($config, true, $owner, true, $item, 'or');
        $linkPlan = parent::linkPlan($config, $owner, $item);
        
        $plan = $this->plans->steps();
        
        $plan->appendPlan($unlinkPlan);
        $plan->appendPlan($linkPlan);

        return $plan;
    }
    
    public function unlinkPlan($side, $items)
    {
        $config = $side->config();
        
        if($side->type() === 'owner') {
            return $this->getUnlinkPlan($config, false, null, true, $items);
        }else{
            return $this->getUnlinkPlan($config, true, $items, false, null);
        }
    }

    public function linkProperties($config, $owner, $item)
    {
        $this->processProperty('item', $config, $owner, 'set', $item);
        $this->processProperty('owner', $config, $item, 'set', $owner);
    }

    
    protected function processProperty($type, $config, $entity, $action, $value = null, $unsetRelated = true)
    {
        if(!$this->isEntityValue($entity))
            return;
        
        if($type === 'owner') {
            $propertyName = $config->itemOwnerProperty;
            $opposing = 'item';
        }else{
            $propertyName = $config->ownerItemProperty;
            $opposing = 'owner';
        }
        
        $property = $entity->getRelationshipProperty($propertyName);
        
        if($unsetRelated) {
            if($property->isLoaded() && $property->value() !== null) {
                $this->processProperty($opposing, $config, $property->value(), 'set', null, false);
            }
        }
        
        if($action === 'set' && !$this->isEntityValue($value))
            $action = 'reset';
        
        if($action === 'reset') {
            $property->reset();
        }else{
            $property->setValue($value);
        }
    }
    
    public function unlinkProperties($side, $entity)
    {
        $this->processProperty($side->type(), $side->config(), $entity, 'set', null);
    }
    
    public function resetProperties($side, $items)
    {
        if(!is_array($items))
            $items = array($items);
        
        foreach($items as $item)
            $this->processProperty($side->type(), $side->config(), $item, 'reset');
    }
}
