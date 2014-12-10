<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property;

class Entity extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Entity\Single
{

    protected function load()
    {
        return $this->handler->loadProperty($this->side, $this->model);
    }

    protected function linkPlan($value)
    {
        $config = $this->side->config();
        list($owner, $item) = $this->getSides($value);
        return $this->handler->linkPlan($config, $owner, $item);
    }

    protected function setProperties($value)
    {
        $config = $this->side->config();
        list($owner, $item) = $this->getSides($value);
        $this->handler->linkProperties($config, $owner, $item);
    }

    protected function unlinkPlan()
    {
        return $this->handler->unlinkPlan($this->side, $this->model);
    }

    protected function unsetProperties()
    {
        $this->handler->unlinkProperties($this->side, $this->model);
    }

    protected function getSides($opposing)
    {
        if ($this->side->type() === 'item')
            return array($this->model, $opposing);

        return array($opposing, $this->model);
    }
}
