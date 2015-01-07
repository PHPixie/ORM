<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Property;

abstract class Entity extends \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity
           implements \PHPixie\ORM\Relationships\Relationship\Property\Entity\Data
{
    protected $handler;
    
    protected function load()
    {
        $config = $this->side->config();
        $this->handler->loadProperty($config, $this->entity);
    }
    
    abstract public function asData($recursive = false);
}