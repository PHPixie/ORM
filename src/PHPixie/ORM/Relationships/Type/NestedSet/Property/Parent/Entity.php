<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Property\Parent;

class Entity extends \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity\Single
             implements \PHPixie\ORM\Relationships\Relationship\Property\Entity\Query
{
    public function query()
    {
        return $this->handler->query($this->side, $this->entity);
    }
    
    public function allQuery()
    {
        return $this->handler->query($this->side, $this->entity, true);
    }

    protected function load()
    {
        $this->handler->loadProperty($this->side, $this->entity);
    }

    protected function processSet($parent)
    {
        $config = $this->side->config();
        $plan = $this->handler->linkPlan($config, $parent, $this->entity);
        $plan->execute();
        $this->handler->processAdd($config, $parent, $this->entity);
    }

    public function remove()
    {
        $plan = $this->handler->unlinkPlan($this->side, array($this->entity));
        $plan->execute();
        $this->handler->processRemove($this->side, $this->entity);
        return $this;
    }

    public function asData($recursive = false)
    {
        if($recursive) {
            $config = $this->side->config();
            $childrenProperty = $this->entity->getRelationshipProperty($config->childrenProperty, false);
            if($childrenProperty->isLoaded()) {
                return null;
            }
        }

        return parent::asData($recursive);
    }
}
