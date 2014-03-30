<?php

namespace PHPixie\ORM;

class Mapper
{
    protected $orm;
    protected $loaders;
    protected $groupMapper;

    public function __construct($orm, $loaders, $groupMapper, $repositoryRegistry)
    {
        $this->orm = $orm;
        $this->loaders = $loaders;
        $this->groupMapper = $groupMapper;
    }

    public function mapDelete($query)
    {
        $plan = $this->orm->plan();
        $modelName = $query->modelName();
        $repository = $this->repositoryRegistry->get($modelName);

        $dbQuery = $repository->query('delete');
        $this->groupMapper->mapConditions($dbQuery, $query->conditions(), $modelName, $plan);
        $plan->push($this->steps->query($dbQuery))

        return $plan;
    }
.
    public function mapUpdate($query, $data)
    {
        $plan = $this->orm->plan();
        $modelName = $query->modelName();
        $repository = $this->repositoryRegistry->get($modelName);

        $dbQuery = $repository->query('update');
        $db->query->data($data);
        $this->groupMapper->mapConditions($dbQuery, $query->conditions(), $modelName, $plan);
        $plan->push($this->steps->query($dbQuery));

        return $plan;
    }

    public function mapFind($query, $preload)
    {
        $modelName = $query->modelName();
        $resultPlan = $this->orm->resultPlan($modelName);
        $repository = $this->repositoryRegistry->get($modelName);

        $dbQuery = $repository->query('select');
        $this->groupMapper->mapConditions($dbQuery, $query->conditions(), $modelName, $resultPlan->requiredPlan());
        $resultStep = $this->steps->reusableResult($dbQuery);
        $plan->setResultStep($resultStep);

        foreach($preload as $relationship)
            $this->addPreloaders($relationship, $model, $plan->loader(), $plan->preloadPlan());

        return $plan;
    }

    protected function addPreloaders($relationship, $model, $loader, $plan)
    {
        $path = explode('.', $relationship);
        foreach ($path as $rel) {
            $preloader = $loader->getPreloader($relationship);
            if ($preloader === null) {
                $preloader = $this->buildPreloader($model, $relationship, $loader->resultStep(), $plan);
                $loader->setPreloader($relationship, $loader);
            }
            $model = $preloader->modelName();
            $loader = $preloader->getLoader();
        }
    }

    protected function buildPreloader($model, $relationship, $resultStep, $plan)
    {
        $registry = $this->repositoryRegistry->get($model);
        $loader = $this->loaders->singleUseResult($registry);

        $side = $this->relationshipRegistry->getSide($model, $relationship);
        $handler = $this->orm->handler($side->relationshipType());

        return $handler->preloader($side, $loader, $resultStep, $preloadPlan);
    }
}
