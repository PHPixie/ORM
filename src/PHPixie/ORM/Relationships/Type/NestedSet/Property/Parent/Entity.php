<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Property\Parent;

class Entity extends   \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity
             implements \PHPixie\ORM\Relationships\Relationship\Property\Entity\Data,
                        \PHPixie\ORM\Relationships\Relationship\Property\Entity\Query
{

    public function query()
    {
        return $this->handler->query($this->side, $this->entity);
    }

    protected function load()
    {
        $this->handler->loadProperty($this->side, $this->entity);
    }

    public function asData($recursive = false)
    {
        $value = $this->value();
        if ($value === null)
            return null;

        return $value->asObject($recursive);
    }

}
