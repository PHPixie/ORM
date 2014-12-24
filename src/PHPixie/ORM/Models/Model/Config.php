<?php

namespace PHPixie\ORM\Models\Model;

abstract class Config extends \PHPixie\ORM\Configs\Config
{
    public $type;
    public $model;
    
    public function __construct($inflector, $modelName, $config)
    {
        $this->type = $this->type();
        $this->model = $modelName;
        parent::__construct($inflector, $config);
    }
    
    protected abstract function type();
}