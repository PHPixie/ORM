<?php

namespace PHPixie\ORM\Model\Data;

abstract class Data
{
    protected $originalData;

    public function __construct($originalData)
    {
        $this->originalData = $originalData;
    }

    public function setCurrentAsOriginal()
    {
        $this->originalData = $this->currentData();
    }

    abstract public function diff();
    abstract public function currentData();
    abstract public function properties();
}
