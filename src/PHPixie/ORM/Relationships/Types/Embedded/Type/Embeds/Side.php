<?php

namespace PHPixie\ORM\Relationships\Types\Embed;

class Side extends PHPixie\ORM\Relationship\Side
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
