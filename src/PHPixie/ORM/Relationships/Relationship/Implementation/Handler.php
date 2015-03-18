<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation;

abstract class Handler
{
    protected $models;
    protected $planners;
    protected $plans;
    protected $steps;
    protected $loaders;
    protected $mappers;
    protected $relationship;

    public function __construct($models, $planners, $plans, $steps, $loaders, $mappers, $relationship)
    {
        $this->models             = $models;
        $this->planners           = $planners;
        $this->plans              = $plans;
        $this->steps              = $steps;
        $this->loaders            = $loaders;
        $this->mappers            = $mappers;
        $this->relationship       = $relationship;
    }

    protected function getLoadedProperty($model, $propertyName)
    {
        if ($model === null)
            return null;

        $property = $model->getRelationshipProperty($propertyName, false);
        if ($property === null || !$property->isLoaded())
            return null;

        return $property;
    }

    protected function assertModelName($model, $requiredModel)
    {
        if ($model->modelName() !== $requiredModel)
            throw new \PHPixie\ORM\Exception\Relationship("Only '$requiredModel' models can be used for this relationship.");
    }

    protected function getPropertyIfLoaded($model, $propertyName)
    {
        $property = $model->getRelationshipProperty($propertyName);
        if ($property === null || !$property->isLoaded())
            return null;
        return $property;
    }
    
    protected function isEntityValue($item)
    {
        if($item === null) {
            return true;
        }
        
        return $item instanceof \PHPixie\ORM\Models\Model\Entity;
    }
}