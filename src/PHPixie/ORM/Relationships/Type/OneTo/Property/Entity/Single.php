<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Property\Entity;

abstract class Single extends \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity\Single
                      implements \PHPixie\ORM\Relationships\Relationship\Property\Entity\Query
{
    public function query()
    {
        return $this->handler->query($this->side, $this->entity);
    }

    protected function processSet($value)
    {
        $plan = $this->linkPlan($value);
        $plan->execute();
        $this->setProperties($value);
    }

    public function remove()
    {
        $plan = $this->unlinkPlan();
        $plan->execute();
        $this->unsetProperties();
        return $this;
    }

    abstract protected function linkPlan($value);
    abstract protected function setProperties($value);
    abstract protected function unlinkPlan();
    abstract protected function unsetProperties();
}
