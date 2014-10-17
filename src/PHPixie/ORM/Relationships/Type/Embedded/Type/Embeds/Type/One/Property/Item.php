<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Property;

class Item extends \PHPixie\ORM\Relationships\Relationship\Property\Model implements \PHPixie\ORM\Relationships\Relationship\Property\Model\Data
{

    public function load()
    {
        $config = $this->side->config();
        return $this->handler->loadProperty($config, $this->model);
    }

    public function create($data = null)
    {
        $config = $this->side->config();
        return $this->handler->offsetCreate($this->model, $config, $key, $data);
    }

    public function add($item, $key = null)
    {
        $this->offsetSet($key, $item);
    }

    public function remove($items)
    {
        $config = $this->side->config();
        $this->handler->removeItems($this->model, $config, $items);
    }

    public function removeAll()
    {
        $config = $this->side->config();
        return $this->handler->removeAllItems($this->model, $config);
    }

}
