<?php

namespace PHPixie\ORM\Driver;

class Mongo extends \PHPixie\Mongo\Driver
{
    public function repository($modelName, $modelConfig)
    {
        if ($modelConfig->get('type', null) === 'embedded')
            return $this->buildEmbeddedRepository($modelName, $modelConfig);
        
        return $this->buildRepository($modelName, $modelConfig);
    }
    
    protected function buildRepository($modelName, $modelConfig)
    {
        return Mongo\Repository($this->orm, $this, $modelName, $modelConfig);
    }
    
    protected function buildEmbeddedRepository($modelName, $modelConfig)
    {
        return Mongo\Embedded\Repository($this->orm, $this, $modelName, $modelConfig);
    }
    
}