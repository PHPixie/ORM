<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many;

class Loader extends \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Loader
{
    protected $offsets;

    public function offsetExists($offset)
    {
        $this->requireOffsets();
        return array_key_exists($offset, $this->offsets);
    }

    public function getByOffset($offset)
    {
        if(!$this->offsetExists($offset)) {
            throw new \PHPixie\ORM\Exception\Loader("Offset $offset does not exist");
        }

        list($ownerOffset, $itemOffset) = $this->offsets[$offset];
        $owner = $this->ownerLoader->getByOffset($ownerOffset);

        $propertyName = $this->config->ownerItemsProperty;
        $property = $owner->relationshipProperty($propertyName);
        return $property->offsetGet($itemOffset);
    }

    protected function requireOffsets()
    {
        if($this->offsets === null)
            $this->updateOffsets();
    }

    protected function updateOffsets()
    {
        $this->offsets = array();
        $count = 0;

        $property = $this->config->ownerItemsProperty;
        foreach($this->ownerLoader as $key => $owner) {
            $itemCount = $owner->relationshipProperty($property)->count();
            for($i=0; $i<$itemCount; $i++) {
                $this->offsets[$count++] = array($key, $i);
            }
        }
    }
}
