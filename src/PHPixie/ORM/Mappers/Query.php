<?php

namespace PHPixie\ORM\Mappers;

class Query
{
    protected $plans;
    protected $steps;
    protected $loaders;
    protected $repositories;
    protected $mappers;

    public function __construct($plans, $steps, $loaders, $repositories, $mappers)
    {
        $this->plans = $plans;
        $this->steps = $steps;
        $this->loaders = $loaders;
        $this->repositories = $repositories;
        $this->mappers = $mappers;
    }

    public function mapCount($query)
    {
        $modelName = $query->modelName();
        $repository = $this->repositories->get($modelName);
        $databaseQuery = $repository->databaseCountQuery();
        $step = $this->steps->count($databaseQuery);
        $plan = $this->plans->count($step);
        
        $this->mapConditions($query, $databaseQuery, $plan);
        
        return $plan;
    }
    
    public function mapUpdate($query, $data)
    {
        $modelName = $query->modelName();
        $repository = $this->repositories->get($modelName);
        $databaseQuery = $repository->databaseUpdateQuery();
        $databaseQuery->data($data);
        
        $step = $this->steps->query($databaseQuery);
        $plan = $this->plans->query($step);
        
        $this->mapConditions($query, $databaseQuery, $plan);
        
        return $plan;
    }
    
    public function mapFind($query, $preload)
    {
        $modelName = $query->modelName();
        $repository = $this->repositories->get($modelName);
        $databaseQuery = $repository->databaseSelectQuery();
        
        $step = $this->steps->reusableResult($databaseQuery);
        $loader = $this->loaders->reusableResult($repository, $step);
        $preloadingProxy = $this->loaders->peloadingProxy($loader);
        $cachingProxy = $this->loaders->cachingProxy($loader);
        
        $plan = $this->plans->loader($cachingProxy);
        $this->mapConditions($query, $databaseQuery, $plan);
        $preloadPlan = $plan->preloadPlan();
        
        $preloadMapper = $this->mappers->preload();
        $preloadMapper->map($preloadingProxy, $modelName, $preload, $step);
        
        return $plan;
    }
    
    protected function mapConditions($query, $databaseQuery, $queryPlan)
    {
        $modelName = $queyr->modelName();
        $conditions   = $query->getConditions();
        $requiredPlan = $queryPlan->requiredPlan();
        $groupMapper = $this->mappers->group();
        $groupMapper->mapConditions($databaseQuery, $conditions, $modelName, $requiredPlan);
    }
    
    
    public function mapDelete($query)
    {
        $modelName = $query->modelName();
        $repository = $this->repositories->get($modelName);
        
        $deletionSides = $this->cascadeMapper->deletionSides($modelName);
        if(empty($deletionSides)) {
            $databaseQuery = $repository->databaseDeleteQuery();
            $step = $this->steps->query($databaseQuery);
            $plan = $this->plans->query($step);
            $this->mapConditions($query, $databaseQuery, $plan);
        }else{
            $selectQuery = $repository->databaseSelectQuery();
            $step = $this->steps->reusableResult($databaseQuery);
            $this->mapConditions($query, $databaseQuery, $plan);
            
            $deleteQuery = $repository->databaseDeleteQuery();
            $idField = $this->repositories->get($modelName)->config()->idField;
            $this->planners->in()->result($deleteQuery, $idField, $step, $idField, $plan);
            
            $step = $this->steps->query($deleteQuery);
            $plan = $this->plans->query($step);
        }
        
        return $plan;
    }
    

    

    

    




}
