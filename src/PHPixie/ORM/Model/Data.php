<?php

namespace PHPixie\ORM\Model;

abstract class Data {

    protected $originalData;
    
    public function __construct($originalData)
    {
        $this->originalData = $originalData;
    }
    
    public function setCurrentAsOriginal()
    {
        $this->originalData = $this->currentData();
    }
    
    abstract public function setModel($model);
    abstract public function getDataDiff();
    abstract public function currentData();
    abstract public function modelProperties();
}