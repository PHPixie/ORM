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
        $plan = $this->unlinkPlan($config, $owner);
        $linkPlan = parent::linkPlan($config, $owner, $item);
        $plan->appendPlan($linkPlan);

        return $plan;
    }
    
    public function unlinkPlan($side, $model)
    {
        //return $this->getUnlinkPlan($config, true, $owners, false, null);
    }

    public function setItemOwner($config, $item, $owner)
    {
        $this->linkProperty($item, $config->itemProperty, $config->ownerProperty, $owner);
        $this->linkProperty($owner, $config->ownerProperty, $config->itemProperty, $item);
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
        
        if($unlinkRelated)) {
            
            if($property->isLoaded() && $property->value() !== null) {
                $this->unlinkSideProperty($opposing, $config, $property->value(), 'set', null, false);
            }
        }
        
        if($action === 'reset') {
            $propery->reset();
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
