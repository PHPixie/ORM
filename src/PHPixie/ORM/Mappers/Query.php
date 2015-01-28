<?php

namespace PHPixie\ORM\Mappers;

class Query
{
    protected $models;
    protected $mappers;
    protected $plans;
    protected $steps;
    protected $loaders;
    
    protected $databaseModel;

    public function __construct($models, $mappers, $plans, $steps, $loaders)
    {
        $this->models  = $models;
        $this->mappers = $mappers;
        $this->plans   = $plans;
        $this->steps   = $steps;
        $this->loaders = $loaders;
        
        $this->databaseModel = $models->database();
    }

    public function mapCount($query)
    {
        $modelName = $query->modelName();
        $repository = $this->databaseModel->repository($modelName);
        $databaseQuery = $repository->databaseCountQuery();
        $step = $this->steps->count($databaseQuery);
        $plan = $this->plans->count($step);
        
        $this->mapConditions($query, $databaseQuery, $plan);
        
        return $plan;
    }
    
    public function mapUpdate($query, $update)
    {
        $modelName = $query->modelName();
        $repository = $this->databaseModel->repository($modelName);
        $databaseQuery = $repository->databaseUpdateQuery();
        
        $step = $this->steps->query($databaseQuery);
        $plan = $this->plans->query($step);
        
        $this->mapConditions($query, $databaseQuery, $plan);
        $this->mappers->update()->map($databaseQuery, $update);
        
        return $plan;
    }
    
    public function mapFind($query, $preload = null)
    {
        $modelName = $query->modelName();
        $repository = $this->databaseModel->repository($modelName);
        $databaseQuery = $repository->databaseSelectQuery();
        
        $resultStep = $this->steps->reusableResult($databaseQuery);
        $loader = $this->loaders->reusableResult($repository, $resultStep);
        
        $preloadingProxy = null;
        
        if($preload !== null) {
            $preloadingProxy = $this->loaders->preloadingProxy($loader);
            $loader = $preloadingProxy;
        }
        
        $loader = $this->loaders->cachingProxy($loader);
        
        $plan = $this->plans->loader($resultStep, $loader);
        $this->mapConditions($query, $databaseQuery, $plan);
        
        if($preload !== null) {
            $preloadPlan = $plan->preloadPlan();
            $preloadMapper = $this->mappers->preload();
            $preloadMapper->map($preloadingProxy, $modelName, $preload, $resultStep, $plan);
        }
        
        return $plan;
    }
    
    protected function mapConditions($query, $databaseQuery, $queryPlan)
    {
        $modelName           = $query->modelName();
        $conditions          = $query->getConditions();
        $requiredPlan        = $queryPlan->requiredPlan();
        
        $this->mappers->conditions()->map(
            $databaseQuery,
            $conditions,
            $modelName,
            $requiredPlan
        );
    }
    
    
    public function mapDelete($query)
    {
        $deleteMapper = $this->mappers->cascadeDelete();
        $modelName = $query->modelName();
        $repository = $this->databaseModel->repository($modelName);
    
        $deleteQuery = $repository->databaseDeleteQuery();
        $step = $this->steps->query($deleteQuery);
        $plan = $this->plans->query($step);
        
        if($deleteMapper->isModelHandled($modelName)) {
            $selectQuery = $repository->databaseSelectQuery();
            $this->mapConditions($query, $selectQuery, $plan);
            $requiredPlan = $plan->requiredPlan();
            $deleteMapper->map($deleteQuery, $selectQuery, $modelName, $requiredPlan);
            
        }else{
            $this->mapConditions($query, $deleteQuery, $plan);
        }
        
        return $plan;
    }
    
}
