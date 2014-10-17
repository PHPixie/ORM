<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Property\Model;

class Items extends PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Property\Model implements \ArrayAccess, \Countable,
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
        $this->setItem($item);
    }

    public function remove()
    {
        $config = $this->side->config();
        $this->handler->removeItem($this->model, $config);
    }

}
