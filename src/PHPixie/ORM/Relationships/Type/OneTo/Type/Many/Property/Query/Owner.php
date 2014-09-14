<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query;

class Owner extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Query\Single
{
    protected function linkPlan($owner)
    {
        return $this->handler->linkPlan($this->side->config(), $owner, $this->query);
    }

    protected function resetProperties($owner)
    {
        return $this->handler->resetProperties($this->side, $owner);
    }

    protected function unlinkPlan()
    {
        return $this->handler->unlinkItemsPlan($this->side->config(), $this->query);
    }

}
