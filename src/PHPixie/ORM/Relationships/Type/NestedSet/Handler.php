<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet;

class Handler extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
              implements \PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Database,
                         \PHPixie\ORM\Relationships\Relationship\Handler\Preloading
{
    protected $opposingMap = array(
        'parent'      => 'children',
        'children'    => 'parent',
        'allParents'  => 'allChildren',
        'allChildren' => 'allParents'
    );

    public function linkPlan($config, $parent, $child)
    {
        $this->assertEntity($config, $parent);
        $this->assertEntity($config, $child);

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

        $moveStep = $this->relationship->steps()->moveChild($repository, $config, $childStep, $parent->id());
        $plan->add($moveStep);
        return $plan;
    }

    public function unlinkPlan($config, $entity)
    {
        $this->assertEntity($config, $entity);

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

        $removeStep = $this->relationship->steps()->removeChild($repository, $config, $nodeStep);
        $plan->add($removeStep);
        return $plan;
    }

    public function query($side, $related)
    {
        $config = $side->config();
        $property = $this->opposingMap[$side->type()].'Property';
        $property = $config->$property;

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
        if(empty($conditions) && in_array($side->type(), array('parent', 'allParents'))) {
            $builder
                ->startConditionGroup($group->logic(), !$group->isNegated())
                    ->where($config->depthKey, null)
                    ->orWhere($config->depthKey, 0)
                ->endGroup();
            return;
        }

        $subquery = $repository->databaseSelectQuery();

        $this->mappers->conditions()->map(
            $subquery,
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
            $builder->where($config->rootIdKey, null);
            $placeholder = $builder->addPlaceholder('or', true);
            $builder->endGroup();
        }

        $mapStep = $this->relationship->steps()->mapQuery(
            $config,
            in_array($side->type(), array('parent', 'allParents')) ? 'children' : 'parent',
            $placeholder,
            $resultStep,
            in_array($side->type(), array('parent', 'children'))
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

    public function handleDelete($side, $reusableResult, $plan, $sidePath)
    {
        $config = $side->config();

        $assertLeavesStep = $this->relationship->steps()->assertSafeDelete(
            $this->repository($config),
            $config,
            $reusableResult
        );

        $plan->add($assertLeavesStep);
    }

    public function processAdd($config, $parent, $child)
    {
        $property = $this->getLoadedProperty($parent, $config->childrenProperty);
        if($property !== null) {
            $property->value()->add(array($child));
        }

        $parentProperty = $this->removeFromCurrentParent($config, $child);
        $parentProperty->setValue($parent);
    }

    public function processRemove($config, $node)
    {
        $parentProperty = $this->removeFromCurrentParent($config, $node);
        $parentProperty->setValue(null);
    }

    protected function removeFromCurrentParent($config, $node)
    {
        $property = $node->getRelationshipProperty($config->parentProperty);

        if($property->isLoaded()) {
            $oldParent = $property->value();
            $oldParentProperty = $this->getLoadedProperty($oldParent, $config->childrenProperty);
            if($oldParentProperty !== null) {
                $oldParentProperty->value()->remove(array($node));
            }
        }

        return $property;
    }

    protected function assertEntity($config, $entity)
    {
        if(!($entity instanceof \PHPixie\ORM\Models\Type\Database\Entity)) {
            throw new \PHPixie\ORM\Exception\Relationship("Only entities can be used");
        }

        $this->assertModelName($entity, $config->model);
    }

    protected function repository($config)
    {
        return $this->models->database()->repository($config->model);
    }
}
