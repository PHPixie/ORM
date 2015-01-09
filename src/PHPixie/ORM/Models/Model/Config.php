<?php

namespace PHPixie\ORM\Models\Model;

abstract class Config extends \PHPixie\ORM\Configs\Config
{
    public $type;
    public $model;
    
    public function __construct($inflector, $modelName, $configSlice)
    {
        $this->type = $this->type();
        $this->model = $modelName;
        parent::__construct($inflector, $configSlice);
    }
    
    protected abstract function type();
}