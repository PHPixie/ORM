<?php

namespace PHPixie\ORM\Models\Model;

abstract class Config extends \PHPixie\ORM\Configs\Config
{
    public $type;
    public $modelName;
    
    public function __construct($inflector, $config, $modelName)
    {
        $this->type = $this->type();
        $this->modelName = $modelName;
        parent::__construct($inflector, $config);
    }
    
    protected abstract function type();
}