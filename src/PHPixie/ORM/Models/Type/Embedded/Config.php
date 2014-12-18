<?php

namespace PHPixie\ORM\Models\Type\Database;

class Config extends \PHPixie\ORM\Models\Model\Config
{
    
    protected function type()
    {
        return 'embedded';
    }
    
    protected function processConfig($config, $inflector)
    {
        
    }
    
}