<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Property\Children;

class Entity extends   \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity
            implements \PHPixie\ORM\Relationships\Relationship\Property\Entity\Data,
                       \PHPixie\ORM\Relationships\Relationship\Property\Entity\Query
{

    public function query()
    {
        return $this->handler->query($this->side, $this->entity);
    }

    protected function load()
    {
        $this->handler->loadProperty($this->side, $this->entity);
    }

    public function allQuery()
    {
        return $this->handler->query($this->side, $this->entity, true);
    }
                           
    public function add($child)
    {
        $config = $this->side->config();
        $plan = $this->handler->linkPlan($config, $this->entity, $child);
        $plan->execute();
        $this->handler->processAdd($config, $this->entity, $child);
        return $this;
    }

    public function removeAll()
    {
        $plan = $this->handler->unlinkPlan($this->side, array($this->entity));
        $plan->execute();
        $this->handler->processRemove($this->side, $this->entity);
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
