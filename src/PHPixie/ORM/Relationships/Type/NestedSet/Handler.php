<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet;

class Handler extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
                    implements \PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Database,
                               \PHPixie\ORM\Relationships\Relationship\Handler\Preloading
{
    public function linkPlan($config, $parent, $child)
    {
        $plan = $this->plans->steps();
        $repository = $this->repository($config);
        
        $childQuery = $repository->databaseSelectQuery();
        $this->planners->in()->items(
            $childQuery,
            $config->model,
            [$parent, $child],
            $plan
        );
        
        $childStep = $this->steps->iteratorResult($childQuery);
        $plan->add($childStep);

        $moveStep = new Steps\MoveChild($repository, $config, $childStep, $parent->id());
        $plan->add($moveStep);
        return $plan;
    }
                                   
    public function mapDatabaseQuery($builder, $side, $group, $plan ){}
    public function mapPreload($side, $property, $result, $plan)
    {
        $config = $side->config();
        $repository = $this->repository($config);

        $query = $repository->databaseSelectQuery();
        
        $mapStep = new Steps\Map\Children($config, $query, $result);
        
        $preloadStep = $this->steps->reusableResult($query);
        $plan->add($preloadStep);
        
        $loader = $this->loaders->reusableResult($repository, $preloadStep);
        $preloadingProxy = $this->loaders->preloadingProxy($loader);
        $cachingProxy = $this->loaders->cachingProxy($preloadingProxy);
        
        $this->mappers->preload()->map(
            $preloadingProxy,
            $repository->modelName(),
            $property->preload(),
            $preloadStep,
            $plan
        );
        
        return $this->relationship->preloader(
            $side,
            $repository->config(),
            $preloadStep,
            $cachingProxy
        );
    }
    
    protected function repository($config)
    {
        return $this->models->database()->repository($config->model);
    }
}
