<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany\Property;

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
        list($left, $right) = $this->getSides($items);
        $plan = $this->handler->linkPlan($config, $left, $right);
        $plan->execute();
        $this->handler->linkProperties($config, $left, $right);
        return $this;
    }

    public function remove($items)
    {
        $config = $this->side->config();
        list($left, $right) = $this->getSides($items);
        $plan = $this->handler->unlinkPlan($config, $left, $right);
        $plan->execute();
        $this->handler->unlinkProperties($config, $left, $right);
        return $this;
    }

    public function removeAll()
    {
        $plan = $this->handler->unlinkAllPlan($this->side, $this->entity);
        $plan->execute();
        $this->handler->unlinkAllProperties($this->side, $this->entity);
        return $this;
    }

    protected function getSides($opposing)
    {
        if ($this->side->type() === 'right')
            return array($this->entity, $opposing);

        return array($opposing, $this->entity);
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
