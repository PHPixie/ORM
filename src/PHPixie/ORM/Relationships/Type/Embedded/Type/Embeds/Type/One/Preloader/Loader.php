<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Preloader;

class Loader extends \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Preloader\Loader
{
    protected function getModelByOffset($offset)
    {
        $ownerOffset = $this->offsets[$offset];
        $owner = $this->ownerLoader->getByOffset($ownerOffset);

        $propertyName = $this->config->ownerItemProperty;
        $property = $owner->relationshipProperty($propertyName);
        return $property->value();
    }

    protected function updateOffsets()
    {
        $this->offsets = array();
        $count = 0;

        $propertyName = $this->config->ownerItemProperty;
        foreach($this->ownerLoader as $key => $owner) {
            $property = $owner->relationshipProperty($propertyName);
            if($property->exists()) {
                $this->offsets[$count++] = $key;
            }
        }
    }
}
