<?php

namespace \PHPixie\ORM\Planners\Planner;

class Cascade extends \PHPixie\ORM\Planners\Planner\Strategy
{
	
	public function deletion($selectQuery, $sides, $repository, $plan){
		$resultStep = $this->steps->reusableResult($selectQuery);
		$plan->add($resultStep);
		foreach($sides as $side) {
			$handler = $this->ormBuilder->relationship(side->relationship())->handler();
			$handler->handleDeletion($repository->modelName(), $side, $resultStep, $plan);
		}
		$deleteQuery = $repository->databaseQuery('delete');
		$idField = $repository->idField();
		$this->planners->in()->result($deleteQuery, $idField, $resultStep, $idField);
		return $deleteQuery;
	}
	
	public function handledDeletionSides($modelName, $relationshipMap)
	{
		
	}
	
}