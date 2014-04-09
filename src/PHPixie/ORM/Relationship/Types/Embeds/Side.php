<?php

namespace PHPixie\ORM\Relationships\Types\Embed;

class Side extends PHPixie\ORM\Relationship\Side
{
    
    protected $propertyName;
    
    public function __construct($propertyName, $config)
    {
        parent::__construct('embeds', $config);
        $this->propertyName = $propertyName;
        
    }
    
    public function modelName()
    {
        return $this->config->modelName;
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
