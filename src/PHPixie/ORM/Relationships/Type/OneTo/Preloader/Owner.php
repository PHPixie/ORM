<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Preloader;

abstract class Owner extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Single
{
    protected $ownerKey;
    
    protected function mapItems()
    {
        $this->ownerKey = $this->side->config()->ownerKey;
        $idField = $this->loader->repository()->idField();
        $this->idOffsets = array_flip($this->loader->reusableResult()->getField($idField));
    }

    public function getMappedIdFor($model)
    {
        return $model->getField($this->ownerKey);
    }
}
