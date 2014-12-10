<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result;

abstract class Single extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result
{
    protected function getMappedFor($model)
    {
        $id = $this->getMappedIdFor($model);
        
        if($id === null)
            return null;
        
        return $this->getEntity($id);
    }
    
    abstract protected function getMappedIdFor($model);
}
