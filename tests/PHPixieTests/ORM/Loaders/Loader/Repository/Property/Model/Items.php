<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property\Model;

class Items extends \PHPixie\ORM\Relationships\Type\Embeds\Property\Model
            implements \ArrayAccess, \Countable
{

    protected function load()
    {
        $config = $this->side->config();
        return $this->handler->loadProperty($config, $this->model);
    }

    public function offsetExists($key)
    {
        return $this->value()->offsetExists($key);
    }

    public function offsetGet($key)
    {
        return $this->value()->getByOffset($key);
    }

    public function count()
    {
        return $this->value()->count();
    }

    public function offsetSet($key, $item)
    {
        $config = $this->side->config();
        $this->handler->offsetSet($this->model, $config, $key, $item);
    }

    public function offsetUnset($key)
    {
        $config = $this->side->config();
        $this->handler->offsetUnset($this->model, $config, $key);
    }

    public function create($key = null, $data = null)
    {
        $config = $this->side->config();
        return $this->handler->offsetCreate($this->model, $config, $key, $data);
    }

    public function add($item, $key = null)
    {
        $this->offsetSet($key, $item);
        return $this;
    }

    public function remove($items)
    {
        $config = $this->side->config();
        $this->handler->removeItems($this->model, $config, $items);
        return $this;
    }

    public function removeAll()
    {
        $config = $this->side->config();
        $this->handler->removeAllItems($this->model, $config);
        return $this;
    }

    public function asData($recursive = false)
    {
        $data = array();
        foreach($this->value() as $model)
            $data[] = $model->asObject($recursive);
        return $data;
    }
}
