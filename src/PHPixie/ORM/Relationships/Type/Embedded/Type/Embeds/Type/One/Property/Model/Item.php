<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Property\Model;

class Item extends \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Property\Model
{

    protected function load()
    {
        $config = $this->side->config();
        return $this->handler->loadProperty($config, $this->model);
    }

    public function create($data = null)
    {
        $config = $this->side->config();
        return $this->handler->createItem($this->model, $config, $data);
    }

    public function set($item)
    {
        if($item === null)
            return $this->remove();

        $config = $this->side->config();
        $this->handler->setItem($this->model, $config, $item);
        return $this;
    }

    public function remove()
    {
        $config = $this->side->config();
        $this->handler->removeItem($this->model, $config);
        return $this;
    }

    public function asData($recursive = false)
    {
        $value = $this->value();
        if ($value === null)
            return null;

        return $value->asObject($recursive);
    }
}
