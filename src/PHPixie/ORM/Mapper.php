<?php

namespace PHPixie\ORM;

class Mapper
{
    protected $plans;
    protected $steps;
    protected $loaders;
    protected $repositories;
    protected $groupMapper;
    protected $cascadeMapper;

    public function __construct($plans, $steps, $loaders, $repositories, $groupMapper, $cascadeMapper)
    {
        $this->plans = $plans;
        $this->steps = $steps;
        $this->loaders = $loaders;
        $this->repositories = $repositories;
        $this->groupMapper = $groupMapper;
        $this->cascadeMapper = $cascadeMapper;
    }

    public function mapDelete($query)
    {
        $modelName = $query->modelName();
        $repository = $this->repositories->get($modelName);

        $handledSides = $this->cascadeMapper->deletionSides($modelName);
        $hasHandledSides = !empty($handledSides);
        $dbQuery = $repository->query($hasHandledSides? 'select' : 'delete');
        $this->groupMapper->mapConditions($dbQuery, $query->conditions(), $modelName, $plan);

        if ($hasHandledSides)
            $query = $this->cascadeMapper->deletion($query, $handledSides, $repository, $plan);

        $deleteStep = $this->steps->query($query);
        $plan->add($deleteStep);

        $plan->push($this->steps->query($dbQuery));

        return $plan;
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
    
    protected function mapConditions($query, $databaseQuery, $queryPlan)
    {
        $modelName = $queyr->modelName();
        $conditions   = $query->getConditions();
        $requiredPlan = $queryPlan->requiredPlan();
        $this->groupMapper->mapConditions($databaseQuery, $conditions, $modelName, $requiredPlan);
    }
    
    public function mapFind($query, $preload)
    {
        $modelName = $query->modelName();
        $repository = $this->repositories->get($modelName);
        $databaseQuery = $repository->databaseSelectQuery();
        
        $step = $this->steps->reusableResult($databaseQuery);
        $loader = $this->loaders->reusableResultStep($repository, $step);
        $preloadingProxy = $this->loaders->peloadingProxy($loader);
        $plan = $this->plans->loader($loader);
        
        $this->mapConditions($query, $databaseQuery, $plan);
        
        $preloadPlan = $plan->preloadPlan();
        
        foreach($preload as $relationship) {
            $this->addPreloaders($modelName, $relationship, $preloadingProxy, $preloadPlan);
        }
        
        return $plan;
    }

    protected function addPreloaders($model, $relationship, $preloadingProxy, $stepsPlan)
    {
        $path = explode('.', $relationship);
        foreach ($path as $rel) {
            $preloader = $preloadingProxy->getPreloader($relationship);
            if ($preloader === null) {
                $preloader = $this->buildPreloader($model, $relationship, $loader, $plan);
                $loader->setPreloader($relationship, $preloader);
            }
            $model = $preloader->modelName();
            $resultLoader = $preloader->loader();
        }
    }

    protected function buildPreloader($model, $relationship, $loader, $plan)
    {
        $side = $this->relationshipRegistry->getSide($model, $relationship);
        $handler = $this->orm->relationshipType($side->relationshipType())->handler();

        $preloader = $handler->preloader($side, $loader, $plan);
        $preloadingProxy = $this->loaders->peloadingProxy($preloader);
    }

}
