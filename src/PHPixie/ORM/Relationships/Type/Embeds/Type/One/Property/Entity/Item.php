<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\One\Property\Entity;

class Item extends \PHPixie\ORM\Relationships\Type\Embeds\Property\Entity
{

    public function create($data = null)
    {
        $config = $this->side->config();
        $this->handler->createItem($this->entity, $config, $data);
        return $this->value();
    }

    public function set($item)
    {
        if($item === null)
            return $this->remove();

        $config = $this->side->config();
        $this->handler->setItem($this->entity, $config, $item);
        return $this;
    }

    public function exists()
    {
        return $this->value() !== null;   
    }

    public function remove()
    {
        $config = $this->side->config();
        $this->handler->removeItem($this->entity, $config);
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
