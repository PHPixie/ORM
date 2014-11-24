<?php

namespace PHPixie\ORM\Mapper;

class Cascade
{
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

    public function getDeletionSides($modelName)
    {
        $sides = array();
        foreach($this->relationshipMap->modelSides($modelName) as $side)
            if ($side->handleDeletions())
                $sides[] = $side;

        return $sides;
    }
    
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
    
    public function deletion($selectQuery, $modelName)
    {
        $path = $this->mappers->cascadePath();
        $deleteQuery = $repository->databaseDeleteQuery();
        $idField = $this->repositories->get($modelName)->config()->idField;
        $this->planners->in()->result($deleteQuery, $idField, $step, $idField, $plan);
            
        $step = $this->steps->query($deleteQuery);
        $plan = $this->plans->query($step);
    }
}
