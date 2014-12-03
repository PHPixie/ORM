<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds;

abstract class Side extends \PHPixie\ORM\Relationships\Relationship\Side
{
    public function modelName()
    {
        return $this->config->ownerModel;
    }

    public function propertyName()
    {
        return $this->config->ownerProperty();
    }
}
