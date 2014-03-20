<?php

namespace PHPixie\ORM\Relationships\Types\Embed;

class Side extends PHPixie\ORM\Relationship\Side
{
    
    protected $propertyName;
    
    public function __construct($relationship, $propertyName, $config)
    {
        parent::__construct($relationship, 'embeds', $config);
        $this->propertyName = $propertyName;
        
    }
    
    public function modelName()
    {
        return $this->config->model;
    }

    public function propertyName()
    {
        return $this->propertyName;
    }

    public function relationship()
    {
        return 'embeds'
    }
}
