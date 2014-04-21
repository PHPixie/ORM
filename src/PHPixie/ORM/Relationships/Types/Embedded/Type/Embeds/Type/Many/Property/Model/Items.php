<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type\Embeds\Type\Many\Property\Model;

class Items extends PHPixie\ORM\Relationships\Relationship\Property\Model implements \ArrayAccess, \Countable
{
    protected $models = array();

    public function load()
    {
        return $this->handler->propertyLoader($this);
    }

    public function offsetExists($key)
    {
        if (array_key_exists($key, $this->models))
            return true;

        return $this->handler->arrayExistsEmbedded($this->model, $this->config(), $key);
    }

    public function offsetGet($key)
    {
        if (array_key_exists($key, $this->models))
            return $this->models[$key];

        $item = $this->handler->arrayGetEmbedded($this->model, $this->config(), $key);
        $this->models[$key] = $item;

        return $item;
    }

    public function offsetSet($key, $item)
    {
        $config = $this->config();
        $this->handler->arraySetEmbedded($this->model, $config, $key, $item);
        $this->handler->setOwnerProperty($config, $item, $this->model);
        $this->models[$key] = $item;
    }

    public function offsetUnset($key)
    {
        $config = $this->config();
        $this->handler->arrayUnsetEmbedded($this->model, $config, $key);
        $this->handler->unsetOwnerProperty($config, $this->models[$key], null);
        unset($this->models[$key]);
    }

    public function create($key = null)
    {
        $config = $this->config();
        $item = $this->handler->arrayAddEmbedded($this->model, $config, $key);
        $this->handler->setOwnerProperty($this->embedConfig, $item, $this->model);

        if ($key === null) {
            $this->models[$key] = $item;
        } else {
            $this->models[] = $item;
        }

        return $item;
    }

    public function add($item, $key = null)
    {
        if ($key === null)
            $key = $this->count();

        return $this->set($key, $item);
    }

    public function remove($items)
    {
        if (!is_array($items))
            $items = array($items);

        while (!empty($items)) {
            $item = array_pop($items);
            $key = array_search($item, $this->models, true);
            if ($key === false)
                throw new \PHPixie\ORM\Exception\Model("The model to be removed was not found.");
            $this->offsetUnset($key);
        }
    }

    public function count()
    {
        return $this->handler->arrayCountEmbedded($this->model, $this->config());
    }

    public function clear()
    {
        return $this->handler->arrayClear($this->model, $this->config());
    }

    public function reset()
    {
        $this->models = array();
        parent::reset();
    }

    public function setValue($models)
    {
        $this->models = $models;
    }
}
