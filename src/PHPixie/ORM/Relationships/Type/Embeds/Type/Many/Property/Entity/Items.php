<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property\Entity;

class Items extends \PHPixie\ORM\Relationships\Type\Embeds\Property\Entity
            implements \ArrayAccess, \Countable
{

    protected function load()
    {
        $config = $this->side->config();
        $this->handler->loadProperty($config, $this->entity);
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
        $this->handler->offsetSet($this->entity, $config, $key, $item);
    }

    public function offsetUnset($key)
    {
        $config = $this->side->config();
        $this->handler->offsetUnset($this->entity, $config, $key);
    }

    public function create($data = null, $key = null)
    {
        $config = $this->side->config();
        return $this->handler->offsetCreate($this->entity, $config, $key, $data);
    }

    public function add($item, $key = null)
    {
        $this->offsetSet($key, $item);
        return $this;
    }

    public function remove($items)
    {
        $config = $this->side->config();
        $this->handler->removeItems($this->entity, $config, $items);
        return $this;
    }

    public function removeAll()
    {
        $config = $this->side->config();
        $this->handler->removeAllItems($this->entity, $config);
        return $this;
    }

    public function asData($recursive = false)
    {
        $data = array();
        foreach($this->value() as $entity)
            $data[] = $entity->asObject($recursive);
        return $data;
    }
}
