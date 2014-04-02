<?php

namespace PHPixie\ORM\Relationship\Type;

class Handler
{
    protected $orm;
    protected $relationship;
    protected $repositoryRegistry;
    protected $planners;
    protected $steps;
    protected $loaders;

    public function __construct($orm, $relationship, $repositoryRegistry, $planners, $steps, $loaders)
    {
        $this->orm                = $orm;
        $this->relationship       = $relationship;
        $this->repositoryRegistry = $repositoryRegistry;
        $this->planners           = $planners;
        $this->steps              = $steps;
        $this->loaders            = $loaders;
    }

    protected function buildRelatedQuery($modelName, $property, $related)
    {
        return $this->orm->query($modelName)
                                ->related($property, $relatedModel);
    }
    
    protected function getLoadedProperty($model, $propertyName)
    {
        if ($model === null)
            return null;
        
        $property = $model->relationshipProperty($propertyName, false);
        if ($property === null || !$property->loaded())
            return null;
            
        return $property;
    }
}
