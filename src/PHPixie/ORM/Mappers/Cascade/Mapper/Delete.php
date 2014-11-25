<?php

namespace PHPixie\ORM\Mappers\Cascade\Mapper;

class Delete extends \PHPixie\ORM\Mappers\Cascade\Mapper
{
    protected function isSideHandled($side)
    {
        return $side->isDeleteHandled();
    }
    
    public function handleResult($reusableResult, $modelName, $plan, $path)
    {
        if($path->hasModel($modelName))
            throw new Exception();
        
        $sides = $this->getHandledSides($modelName);
        foreach($sides as $side) {
            $sidePath = $path->copy();
            $sidePath->addSide($side);
            $relationship = $this->relationships->get($side->relationshipType());
            $handler = $relationship->handler();
            $handler->handleDelete($side, $reusableResult, $plan, $sidePath);
        }
    }
    
    public function handleDatabaseQuery($selectQuery, $sides, $repository, $plan)
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
}