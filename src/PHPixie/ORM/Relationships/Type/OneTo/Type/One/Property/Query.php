<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property;

class Query extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Query\Single
{
    protected function linkPlan($value)
    {
        $config = $this->side->config();
        list($owner, $item) = $this->getSides($value);
        return $this->handler->linkPlan($config, $owner, $item);
    }

    protected function resetProperties($value)
    {
        return $this->handler->resetProperties($this->side, $value);
    }

    protected function unlinkPlan()
    {
        return $this->handler->unlinkPlan($this->side, $this->query);
    }

    protected function getSides($opposing)
    {
        if ($this->side->type() === 'item')
            return array($this->query, $opposing);

        return array($opposing, $this->query);
    }

}
