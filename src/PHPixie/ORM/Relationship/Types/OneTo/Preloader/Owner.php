<?php

namespace PHPixie\ORM\Relationships\OneTo\Preloader;

abstract class Owner extends \PHPixie\ORM\Relationship\Type\Preloader\Result\Single
{
    
    protected function mapItems()
    {
        $idField = $this->loader->repository()->idField();
        $this->idOffsets = array_flip($this->loader->resultStep->getField($idField));
    }
    
    public function getMappedFor($model)
    {
        $itemKey = $this->config->itemKey;
        return $this->getModel($model->$itemKey);
    }
}
