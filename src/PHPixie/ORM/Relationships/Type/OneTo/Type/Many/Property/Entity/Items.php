<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity;

class Items extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Entity
{
    protected function load()
    {
        $this->handler->loadItemsProperty($this->side, $this->entity);
    }

    public function add($items)
    {
        $config = $this->side->config();
        $plan = $this->handler->linkPlan($config, $this->entity, $items);
        $plan->execute();
        $this->handler->addOwnerItems($config, $this->entity, $items);
        return $this;
    }

    public function remove($items)
    {
        $config = $this->side->config();
        $plan = $this->handler->unlinkPlan($config, $this->entity, $items);
        $plan->execute();
        $this->handler->removeOwnerItems($config, $this->entity, $items);
        return $this;
    }

    public function removeAll()
    {
        $config = $this->side->config();
        $plan = $this->handler->unlinkOwnersPlan($config, $this->entity);
        $plan->execute();
        $this->handler->removeAllOwnerItems($config, $this->entity);
        return $this;
    }

    public function asData($recursive = false)
    {
        $data = array();
        foreach($this->value() as $entity) {
            if(!$entity->isDeleted()) {
                $data[] = $entity->asObject($recursive);
            }
        }
        return $data;
    }
}
