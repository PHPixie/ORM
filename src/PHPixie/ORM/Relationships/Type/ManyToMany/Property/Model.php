<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany\Property;

class Model extends \PHPixie\ORM\Relationships\Relationship\Property\Model
            implements \PHPixie\ORM\Relationships\Relationship\Property\Model\Data,
                       \PHPixie\ORM\Relationships\Relationship\Property\Model\Query
{

    public function query()
    {
        return $this->handler->query($this->side, $this->model);
    }

    protected function load()
    {
        return $this->handler->loadProperty($this->side, $this->model);
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
        $plan = $this->handler->unlinkAllPlan($this->side, $this->model);
        $plan->execute();
        $this->handler->unlinkAllProperties($this->side, $this->model);
        return $this;
    }

    protected function getSides($opposing)
    {
        if ($this->side->type() === 'right')
            return array($this->model, $opposing);

        return array($opposing, $this->model);
    }

    public function asData($recursive = false)
    {
        $data = array();
        foreach($this->value() as $model)
            $data[] = $model->asObject($recursive);
        return $data;
    }

}
