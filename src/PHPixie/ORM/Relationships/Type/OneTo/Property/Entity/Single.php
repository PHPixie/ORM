<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Property\Entity;

abstract class Single extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Entity
{
    public function value()
    {
        $value = parent::value();
        if ($value !== null && $value->isDeleted()) {
            $this->setValue(null);
            return null;
        }

        return $value;
    }

    public function asData($recursive = false)
    {
        $value = $this->value();
        if ($value === null)
            return null;

        return $value->asObject($recursive);
    }

    public function set($value)
    {
        if($value === null || $value->isDeleted())
            return $this->remove();

        $plan = $this->linkPlan($value);
        $plan->execute();
        $this->setProperties($value);
        return $this;
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
