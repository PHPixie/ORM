<?php

namespace PHPixie\ORM\Mappers\Cascade\Mapper;

class Delete extends \PHPixie\ORM\Mappers\Cascade\Mapper
{
    protected $models;
    protected $planners;
    protected $steps;
    
    public function __construct($mappers, $relationships, $models, $planners, $steps, $cascadeDeletMap)
    {
        parent::__construct($mappers, $relationships, $cascadeDeletMap);
        $this->models = $models;
        $this->planners = $planners;
        $this->steps = $steps;
    }
    
    public function handleResult($reusableResult, $modelName, $plan, $path)
    {
        $this->assertDirectionalPath($path, $modelName);
        
        $sides = $this->cascadeMap->getModelSides($modelName);
        foreach($sides as $side) {
            $sidePath = $path->copy();
            $sidePath->addSide($side);
            $relationship = $this->relationships->get($side->relationshipType());
            $handler = $relationship->handler();
            $handler->handleDelete($side, $reusableResult, $plan, $sidePath);
        }
    }
    
    public function handleQuery($selectQuery, $modelName, $plan, $path)
    {
        $repository  = $this->models->database()->repository($modelName);
        $deleteQuery = $repository->databaseDeleteQuery();
        
        $this->mapDeleteQuery($deleteQuery, $selectQuery, $modelName, $plan, $path);
        
        $deleteStep = $this->steps->query($deleteQuery);
        $plan->add($deleteStep);
    }
    
    public function map($deleteQuery, $selectQuery, $modelName, $plan)
    {
        $path = $this->mappers->cascadePath();
        $this->mapDeleteQuery($deleteQuery, $selectQuery, $modelName, $plan, $path);
    }

    protected function mapDeleteQuery($deleteQuery, $selectQuery, $modelName, $plan, $path)
    {
        $resultStep = $this->steps->reusableResult($selectQuery);
        $plan->add($resultStep);
        $this->handleResult($resultStep, $modelName, $plan, $path); 
        
        $repository = $this->models->database()->repository($modelName);
        $idField = $repository->config()->idField;
        $this->planners->in()->result(
            $deleteQuery,
            $idField,
            $resultStep,
            $idField,
            $plan
        );
    }

}