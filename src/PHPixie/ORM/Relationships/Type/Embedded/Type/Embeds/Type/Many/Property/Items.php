<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Property;

class Items extends \PHPixie\ORM\Relationships\Relationship\Property\Model implements \ArrayAccess, \Countable
{
    protected $models = array();

    public function load()
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
        $this->handler->propertyOffsetSet($this->model, $config, $key, $item);
    }

    public function offsetUnset($key)
    {
        $config = $this->side->config();
        $this->handler->propertyOffsetUnset($this->model, $config, $key);
    }

    public function create($key = null)
    {
        $this->handler->propertyOffsetCreate($this->model, $config, $key);
    }

    public function add($item, $key = null)
    {
        $this->offsetSet($key, $item);
    }

    public function remove($items)
    {
        $this->handler->propertyRemoveItems($this->model, $config, $items);
    }

    public function removeAll()
    {
        return $this->handler->propertyRemoveAllItems($this->model, $this->config());
    }

}
