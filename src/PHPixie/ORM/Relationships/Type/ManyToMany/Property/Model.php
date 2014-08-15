<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany\Property;

class Model extends \PHPixie\ORM\Relationships\Relationship\Property\Model
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
        return $this->model;
    }

    public function remove($items)
    {
        if ($items === null)
            return;

        $config = $this->side->config();
        list($left, $right) = $this->getSides($items);
        $plan = $this->handler->unlinkPlan($config, $left, $right);
        $plan->execute();
        $this->handler->unlinkProperties($config, $left, $right);
        return $this->model;
    }

    public function removeAll()
    {
        $config = $this->side->config();
        list($left, $right) = $this->getSides(null);
        $plan = $this->handler->unlinkPlan($config, $left, $right);
        $plan->execute();
        if ($this->loaded && $this->value !== null) {
            $this->handler->unlinkProperties($this->config, $this->value->usedModels(), null);
            $this->value->removeAll();
        }
        return $this->model;
    }

    protected function getSides($opposing)
    {
        if ($this->side->type() === 'right')
            return array($this->model, $opposing);

        return array($opposing, $this->model);
    }

    public function data($recursive = true)
    {
        $data = array();
        foreach($this->value() as $model)
            $data[] = $model->asObject($recursive);

        return $data;
    }

}
