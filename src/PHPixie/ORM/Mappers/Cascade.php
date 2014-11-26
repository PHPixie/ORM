<?php

namespace PHPixie\ORM\Mappers;

abstract class Cascade
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
    public function deletion($selectQuery, $sides, $repository, $plan)
    {
        $resultStep = $this->steps->reusableResult($selectQuery);
        $plan->add($resultStep);
        foreach ($sides as $side) {
            $handler = $this->ormBuilder->relationship($side->relationship())->handler();
            $handler->handleDeletion($repository->modelName(), $side, $resultStep, $plan);
        }
        $deleteQuery = $repository->databaseQuery('delete');
        $idField = $repository->idField();
        $this->planners->in()->result($deleteQuery, $idField, $resultStep, $idField);

        return $deleteQuery;
    }
*
    
 
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
