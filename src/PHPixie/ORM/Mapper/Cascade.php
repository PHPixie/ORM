<?php

namespace PHPixie\ORM\Mapper;

class Cascade
{
	protected $ormBuilder;
	protected $repositoryRegistry;
	protected $relationshipMap;
	protected $planners;
	protected $steps;
	
	public function __construct($ormBuilder, $repositoryRegistry, $relationshipMap, $planners, $steps)
	{
		$this->ormBuilder = $ormBuilder;
		$this->repositoryRegistry = $repositoryRegistry;
		$this->relationshipMap = $relationshipMap;
		$this->planners = $planners;
		$this->steps = $steps;
	}
	
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
	
	public function deletionSides($modelName)
	{
		$sides = array();
		foreach($this->relationshipMap->modelSides($modelName) as $side)
			if ($side-> handleDeletions())
				$sides[] = $side;
				
		return $sides;
	}
	
}