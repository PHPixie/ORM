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
            array($parent, $child),
            $plan
        );
        
        $childStep = $this->steps->iteratorResult($childQuery);
        $plan->add($childStep);

        $moveStep = new Steps\MoveChild($repository, $config, $childStep, $parent->id());
        $plan->add($moveStep);
        return $plan;
    }

    public function unlinkPlan($config, $entity)
    {
        $plan = $this->plans->steps();
        $repository = $this->repository($config);

        $nodeQuery = $repository->databaseSelectQuery();
        $this->planners->in()->items(
            $nodeQuery,
            $config->model,
            array($entity),
            $plan
        );

        $nodeStep = $this->steps->iteratorResult($nodeQuery);
        $plan->add($nodeStep);

        $removeStep = new Steps\RemoveChild($repository, $config, $nodeStep);
        $plan->add($removeStep);
        return $plan;
    }

    public function query($side, $related)
    {
        $config = $side->config();
        if ($side->type() === 'parent') {
            $property = $config->childrenProperty;
        } else {
            $property = $config->parentProperty;
        }

        $repository = $this->repository($config);
        return $repository->query()->relatedTo($property, $related);
    }

    public function loadProperty($side, $entity)
    {
        $query = $this->query($side, $entity);

        $property = $entity->getRelationshipProperty($side->propertyName());

        if($side->type() === 'parent') {
            $property->setValue($query->findOne());
            return;
        }

        $loader = $query->find();
        $editable = $this->loaders->editableProxy($loader);
        $property->setValue($editable);
    }

    public function mapDatabaseQuery($builder, $side, $group, $plan)
    {
        $config = $side->config();
        $repository = $this->repository($config);

        $conditions = $group->conditions();
        if(empty($conditions)) {
            if ($side->type() == 'parent') {
                $builder
                    ->startConditionGroup($group->logic(), !$group->isNegated())
                        ->where($config->depthKey, null)
                        ->orWhere($config->depthKey, 0)
                    ->endGroup();
            } else {
                $builder
                    ->startConditionGroup($group->logic(), !$group->isNegated())
                        ->where($config->leftKey, null)
                        ->orWhere($config->leftKey, '=*', $config->rightKey)
                    ->endGroup();
            }
            return;
        }

        $subquery = $repository->databaseSelectQuery();
        $this->mappers->conditions()->map(
            $subquery,
            $side->type(),
            $config->model,
            $group->conditions(),
            $plan
        );

        $resultStep = $this->steps->iteratorResult($subquery);
        $plan->add($resultStep);

        if(!$group->isNegated()) {
            $placeholder = $builder->addPlaceholder($group->logic());
        }else{
            $builder->startConditionGroup($group->logic());
            $builder->where('rootId', null);
            $placeholder = $builder->addPlaceholder('or', true);
            $builder->endGroup();
        }


        $mapStep = $this->relationship->steps()->mapQuery(
            $config,
            $placeholder,
            $resultStep,
            true
        );

        $plan->add($mapStep);
    }

    public function mapPreload($side, $property, $result, $plan, $relatedLoader)
    {
        $config = $side->config();
        $repository = $this->repository($config);

        $query = $repository->databaseSelectQuery();

        $mapStep = $this->relationship->steps()->mapQuery(
            $config,
            $side->type(),
            $query,
            $result,
            false
        );

        $plan->add($mapStep);

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
            $plan,
            $cachingProxy
        );
        
        $preloader = $this->relationship->preloader(
            $side,
            $repository->config(),
            $preloadStep,
            $cachingProxy,
            $result,
            $relatedLoader
        );

        $preloadingProxy->addPreloader('children', $preloader);
        $preloadingProxy->addPreloader('parent', $preloader);

        return $preloader;
    }
    
    protected function repository($config)
    {
        return $this->models->database()->repository($config->model);
    }
}
