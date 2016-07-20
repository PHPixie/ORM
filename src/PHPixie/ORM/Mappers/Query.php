<?php

namespace PHPixie\ORM\Mappers;

class Query
{
    protected $mappers;
    protected $loaders;
    protected $models;
    protected $plans;
    protected $steps;
    
    protected $databaseModel;

    public function __construct($mappers, $loaders, $models, $plans, $steps)
    {
        $this->mappers = $mappers;
        $this->loaders = $loaders;
        $this->models  = $models;
        $this->plans   = $plans;
        $this->steps   = $steps;
        
        $this->databaseModel = $models->database();
    }

    public function mapCount($query)
    {
        $modelName = $query->modelName();
        $repository = $this->databaseModel->repository($modelName);
        $databaseQuery = $repository->databaseCountQuery();
        $step = $this->steps->count($databaseQuery);
        $plan = $this->plans->count($step);
        
        $this->mapCommon($query, $databaseQuery, $plan);
        
        return $plan;
    }
    
    public function mapUpdate($query, $update)
    {
        $modelName = $query->modelName();
        $repository = $this->databaseModel->repository($modelName);
        $databaseQuery = $repository->databaseUpdateQuery();
        
        $step = $this->steps->query($databaseQuery);
        $plan = $this->plans->query($step);
        
        $this->mapCommon($query, $databaseQuery, $plan);
        $this->mappers->update()->map($databaseQuery, $update);
        
        return $plan;
    }
    
    public function mapFind($query, $preload = null, $fields = null)
    {
        $modelName = $query->modelName();
        $repository = $this->databaseModel->repository($modelName);
        $databaseQuery = $repository->databaseSelectQuery();
        if($fields !== null) {
            $fields[] = $repository->config()->idField;
            $databaseQuery->fields($fields);
        }
        
        $resultStep = $this->steps->reusableResult($databaseQuery);
        $loader = $this->loaders->reusableResult($repository, $resultStep);
        
        $preloadingProxy = null;
        
        if($preload !== null) {
            $preloadingProxy = $this->loaders->preloadingProxy($loader);
            $loader = $preloadingProxy;
        }
        
        $loader = $this->loaders->cachingProxy($loader);
        
        $plan = $this->plans->loader($resultStep, $loader);
        $this->mapCommon($query, $databaseQuery, $plan);
        
        if($preload !== null) {
            $preloadPlan = $plan->preloadPlan();
            $preloadMapper = $this->mappers->preload();
            $preloadMapper->map($preloadingProxy, $modelName, $preload, $resultStep, $preloadPlan, $loader);
        }
        
        return $plan;
    }
    
    protected function mapCommon($query, $databaseQuery, $queryPlan)
    {
        $modelName           = $query->modelName();
        $conditions          = $query->getConditions();
        $requiredPlan        = $queryPlan->requiredPlan();
        
        $this->mappers->conditions()->map(
            $databaseQuery,
            $modelName,
            $conditions,
            $requiredPlan
        );
        
        $offset = $query->getOffset();
        if($offset !== null) {
            $databaseQuery->offset($offset);
        }
        
        $limit = $query->getLimit();
        if($limit !== null) {
            $databaseQuery->limit($limit);
        }
        
        foreach($query->getOrderBy() as $orderBy) {
            if($orderBy->direction() === 'asc') {
                $databaseQuery->orderAscendingBy(
                    $orderBy->field()
                );
            }else{
                $databaseQuery->orderDescendingBy(
                    $orderBy->field()
                );
            }
        }
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
            $this->mapCommon($query, $selectQuery, $plan);
            $requiredPlan = $plan->requiredPlan();
            $deleteMapper->map($deleteQuery, $selectQuery, $modelName, $requiredPlan);
            
        }else{
            $this->mapCommon($query, $deleteQuery, $plan);
        }
        
        return $plan;
    }
    
}
