<?php

namespace PHPixie\ORM\Mappers\Cascade;

abstract class Mapper
{
    protected $relationships;
    protected $relationshipMap;
    
    public function __construct($relationships)
    {
        $this->relationships = $relationships;
        $this->relationshipMap = $relationships->map();
    }
    
    protected function getHandledSides($modelName)
    {
        $sides = array();
        foreach($this->relationshipMap->modelSides($modelName) as $side) {
            if($this->isSideHandled($side)) {
                $sides[]= $side;
            }
        }
        return $sides;
    }
    
    public function isModelHandled($modelName)
    {
        $sides = $this->getHandledSides($modelName);
        return count($sides) > 0;
    }
    
    abstract protected function isSideHandled($side);
    

/*
    
    public function chainDeletion($reusableResult, $modelName, $plan, $path)
    {
        if($path->hasModel($modelName))
            throw new Exception();
        
        $sides = $this->getDeletionSides($modelName);
        foreach($sides as $side) {
            $sidePath = $path->clone();
            $sidePath->addSide($side);
            $relationship = $this->relationships->get($side->relationshipType());
            $handler = $relationship->handler();
            $handler->handleDeletion($side, $reusableResult, $plan, $path);
        }
    }
    
    public function chainDeletionQuery($selectQuery, $modelName, $plan, $path)
    {
        $selectStep = $this->steps->reusableResult($selectQuery);
        $plan->add($selectStep);
        $this->chainDeletion($selectStep, $modelName, $plan, $path);
        
        $deleteQuery = $repository->databaseDeleteQuery();
        $idField = $repository->config()->idField;
        $this->planners->in()->collection(
            $deleteQuery,
            $idField,
            $collection,
            $idField,
            $plan
        );
        $deleteStep = $this->steps->query($deleteQuery);
        $plan->add($deleteStep);
    }
    
    public function deletion($selectQuery, $modelName, $plan)
    {
        $path = $this->mappers->cascadePath();
        $this->chainDeletionQuery($selectQuery, $modelName, $plan, $path);
    }
    */

}
