<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type\Embeds;

class Side extends PHPixie\ORM\Relationships\Relationship\Side
{
    public function modelName()
    {
            return $this->config->ownerModel;
    }

    public function propertyName()
    {
        return $this->config->ownerProperty;
    }
}
