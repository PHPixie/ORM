<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preloader;

class Loader extends \PHPixie\ORM\Relationships\Type\Embeds\Preloader\Loader
{
    public function getModelByOffset($offset)
    {
        list($ownerOffset, $itemOffset) = $this->offsets[$offset];
        $owner = $this->ownerLoader->getByOffset($ownerOffset);

        $propertyName = $this->config->ownerItemsProperty;
        $property = $owner->relationshipProperty($propertyName);
        return $property->offsetGet($itemOffset);
    }

    protected function updateOffsets()
    {
        $this->offsets = array();
        $count = 0;

        $propertyName = $this->config->ownerItemsProperty;
        foreach($this->ownerLoader as $key => $owner) {
            $itemCount = $owner->relationshipProperty($propertyName)->count();
            for($i=0; $i<$itemCount; $i++) {
                $this->offsets[$count++] = array($key, $i);
            }
        }
    }
}
