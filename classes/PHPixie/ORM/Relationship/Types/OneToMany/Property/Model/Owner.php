<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Model;

class Owner extends \PHPixie\ORM\Relationship\Types\OneToMany\Property\Model
{
    public function load()
    {
        return $this->query()->find();
    }

    public function set($owner)
    {
        $plan = $this->handler->linkPlan($this->side->config(), $owner, $this->model);
        $plan->execute();
    }

    public function unlink()
    {
        $plan = $this->handler->unlinkItemPlan($this->side->config(), $this->model);
        $plan->execute();
    }

}
