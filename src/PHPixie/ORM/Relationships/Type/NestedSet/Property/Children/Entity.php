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

    public function add($items)
    {
        $config = $this->side->config();
        $plan = $this->handler->linkPlan($config, $this->entity, $items);
        $plan->execute();
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
