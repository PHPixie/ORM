<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result;

abstract class Single extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result
{
    public function getMappedFor($model)
    {
        $id = $this->getMappedIdFor($model);
        
        if($id === null)
            return null;
        
        return $this->getModel($id);
    }
    
    abstract protected function getMappedIdFor($model);
}
