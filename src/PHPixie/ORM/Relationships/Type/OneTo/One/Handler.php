<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One;

class Handler extends \PHPixie\ORM\Relationships\Type\OneTo\Handler
{
    public function loadProperty($side, $related)
    {
        return parent::loadSingleProperty($side, $related);
    }
    
    public function linkPlan($config, $owner, $item)
    {
        $plan = $this->getUnlinkPlan($config, true, $owner, true, $item, 'or');
        $linkPlan = parent::linkPlan($config, $owner, $item);
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

    
    protected function processProperty($type, $config, $model, $action, $value = null, $unsetRelated = true)
    {
        if($model instanceof \PHPixie\ORM\Query)
            return;
        
        if($type === 'owner') {
            $propertyName = $config->itemOwnerProperty;
            $opposing = 'item';
        }else{
            $propertyName = $config->ownerItemProperty;
            $opposing = 'owner';
        }
        
        $property = $model->relationshipProperty($propertyName);
        
        if($unsetRelated) {
            
            if($property->isLoaded() && $property->value() !== null) {
                $this->processProperty($opposing, $config, $property->value(), 'set', null, false);
            }
        }
        if($action === 'set' && $value instanceof \PHPixie\ORM\Query)
            $action = 'reset';
        
        if($action === 'reset') {
            $property->reset();
        }else{
            $property->setValue($value);
        }
    }
    
    public function unlinkProperties($side, $model)
    {
        $this->processProperty($side->type(), $side->config(), $model, 'set', null);
    }
    
    public function resetProperties($side, $items)
    {
        if(!is_array($items))
            $items = array($items);
        
        foreach($items as $item)
            $this->processProperty($side->type(), $side->config(), $item, 'reset');
    }
}
