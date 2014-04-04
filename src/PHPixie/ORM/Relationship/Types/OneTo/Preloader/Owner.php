<?php

namespace PHPixie\ORM\Relationships\OneTo\Preloader;

class Owner extends \PHPixie\ORM\Relationship\Type\Preloader\Result\Single
{
    protected $itemKey;
    
    public function __construct()
    {
        $this->itemKey = $this->side-> config()->itemKey;
    }
    
    protected function mapItems()
    {
        $idField = $this->loader->repository()->idField();
        $this->idOffsets = array_flip($this->loader->resultStep->getField($idField));
    }
    
    public function getMappedFor($model)
    {
        $itemKey = $this->itemKey;
        return $this->getModel($model->$itemKey);
    }
}
