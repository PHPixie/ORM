<?php

namespace PHPixie\ORM\Models\Type\Embedded;

class Config extends \PHPixie\ORM\Models\Model\Config
{
    
    protected function type()
    {
        return 'embedded';
    }
    
    protected function processConfig($configSlice, $inflector)
    {
        
    }
    
}