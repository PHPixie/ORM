<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Preloader;

abstract class Owner extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Single
{
    protected $ownerKey;
    
    protected function mapItems()
    {
        $this->ownerKey = $this->side->config()->ownerKey;
        $this->mapIdOffsets();
    }

    protected function getMappedIdFor($model)
    {
        return $model->getField($this->ownerKey);
    }
}
