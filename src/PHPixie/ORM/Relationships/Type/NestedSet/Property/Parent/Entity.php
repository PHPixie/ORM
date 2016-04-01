<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Property\Parent;

class Entity extends \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity\Single
             implements \PHPixie\ORM\Relationships\Relationship\Property\Entity\Query
{
    public function query()
    {
        return $this->handler->query($this->side, $this->entity);
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
    }

    public function remove()
    {
        $config = $this->side->config();
        $plan = $this->handler->unlinkPlan($config, $this->entity);
        $plan->execute();
        return $this;
    }
}
