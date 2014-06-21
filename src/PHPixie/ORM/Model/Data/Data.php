<?php

namespace PHPixie\ORM\Model\Data;

abstract class Data
{
    protected $originalData;
    protected $model;

    public function __construct($originalData)
    {
        $this->originalData = $originalData;
    }

    public function setCurrentAsOriginal()
    {
        $this->originalData = $this->currentData();
    }
    
    public function setModel($model)
    {
        $this->model = $model;
    }
    
    public function currentData()
    {
        if($this->model === null)
            return $this->originalData;
        
        return $this->currentModelData();
    }
    
    abstract public function diff();
    abstract protected function currentModelData();
    abstract public function properties();
}
