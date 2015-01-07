<?php

namespace PHPixie\ORM\Relationships\Type\OneTo;

abstract class Handler extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
                    implements \PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Database,
                               \PHPixie\ORM\Relationships\Relationship\Handler\Preloading,
                               \PHPixie\ORM\Relationships\Relationship\Handler\Cascading\Delete
{

    public function query($side, $related)
    {
        $config = $side->config();
        if ($side->type() !== 'owner') {
            $model = $config->itemModel;
            $property = $config->itemOwnerProperty;
        } else {
            $model = $config->ownerModel;
            $property = $config->ownerProperty();
        }

        $repository = $this->getRepository($model);
        return $repository->query()->relatedTo($property, $related);
    }

    public function linkPlan($config, $owner, $items)
    {
        $plan = $this->plans->steps();

        $ownerRepository = $this->getRepository($config->ownerModel);
        $ownerQuery = $ownerRepository->databaseSelectQuery();
        
        $this->addCollectionCondition($ownerQuery, $ownerRepository, $owner, $plan);

        $itemRepository = $this->getRepository($config->itemModel);
        $updateQuery = $itemRepository->databaseUpdateQuery();
        
        $this->addCollectionCondition($updateQuery, $itemRepository, $items, $plan);

        $this->planners->update()->subquery(
            $updateQuery,
            array(
                $config->ownerKey => $this->getIdField($ownerRepository)
            ),
            $ownerQuery,
            $plan
        );
        
        return $plan;
    }

    protected function addCollectionCondition($query, $repository, $items, $plan, $queryField = null, $logic = 'and')
    {
        $idField = $this->getIdField($repository);
        if($queryField === null)
            $queryField = $idField;
        
        $collection = $this->planners->collection($repository->modelName(), $items);
        $this->planners->in()->collection($query, $queryField, $collection, $idField, $plan, $logic);
    }

    protected function getUnlinkPlan($config, $constrainOwners, $owners, $constrainItems, $items, $logic = 'and')
    {
        $plan = $this->plans->steps();

        $itemRepository = $this->getRepository($config->itemModel);
        $updateQuery = $itemRepository->databaseUpdateQuery();
        $updateQuery->set($config->ownerKey, null);

        if ($constrainItems)
            $this->addCollectionCondition($updateQuery, $itemRepository, $items, $plan);

        if ($constrainOwners) {
            $ownerRepository = $this->getRepository($config->ownerModel);
            $this->addCollectionCondition($updateQuery, $ownerRepository, $owners, $plan, $config->ownerKey, $logic);
        }


        return $plan;
    }

    public function mapDatabaseQuery($query, $side, $collectionCondition, $plan)
    {
        $config = $side->config();
        $itemRepository = $this->getRepository($config->itemModel);
        $ownerRepository = $this->getRepository($config->ownerModel);

        if ($side->type() === 'owner') {
            $subqueryRepository = $ownerRepository;
            $queryField = $config->ownerKey;
            $subqueryField = $this->getIdField($ownerRepository);
        } else {
            $subqueryRepository = $itemRepository;
            $queryField = $this->getIdField($ownerRepository);
            $subqueryField = $config->ownerKey;
        }

        $subquery = $subqueryRepository->databaseSelectQuery();
        $this->mappers->conditions()->map(
            $subquery,
            $subqueryRepository->modelName(),
            $collectionCondition->conditions(),
            $plan
        );

        $this->planners->in()->subquery(
            $query,
            $queryField,
            $subquery,
            $subqueryField,
            $plan,
            $collectionCondition->logic(),
            $collectionCondition->isNegated()
        );
    }

    public function handleDelete($modelName, $side, $resultStep, $plan)
    {
        $config = $side->config();
        $itemKey = $config->itemKey;
        $itemModel = $config->itemModel;

        $itemRepository = $this->repositoryRegistry->get($itemModel);
        $ownerRepository = $this->repositoryRegistry->get($modelName);

        $hasHandledSides = false;

        if ($config->onDelete === 'update') {
            $query = $repository->databaseQuery('update')->data(array($itemKey => null));
        } else {
            $handledSides = $this->cascadeMapper->deletionSides($itemModel);
            $hasHandledSides = !empty($handlesSides);
            $query = $repository->databaseQuery($hasHandledSides ? 'select' : 'delete');
        }
        
        $this->planners->in()->result($query, $itemKey, $resultStep, $this->getIdField($ownerRepository));

        if ($hasHandledSides)
            $query = $this->cascadeMapper->deletion($query, $handledSides, $itemRepository, $plan);

        $deleteStep = $this->steps->query($query);
        $plan->add($deleteStep);
    }

    public function mapPreload($side, $preloadProperty, $reusableResult, $plan)
    {
        $config = $side->config();

        $itemRepository = $this->getRepository($config->itemModel);
        $ownerRepository = $this->getRepository($config->ownerModel);

        
        if ($side->type() === 'owner') {
            $preloadRepository = $ownerRepository;
            $queryField = $this->getIdField($ownerRepository);
            $resultField = $config->ownerKey;
        } else {
            $preloadRepository = $itemRepository;
            $queryField = $config->ownerKey;
            $resultField = $this->getIdField($ownerRepository);
        }

        $query = $preloadRepository->databaseSelectQuery();
        $this->planners->in()->result(
                                        $query,
                                        $queryField,
                                        $reusableResult,
                                        $resultField,
                                        $plan
                                    );

        $preloadStep = $this->steps->reusableResult($query);
        $plan->add($preloadStep);
        $loader = $this->loaders->reusableResult($preloadRepository, $preloadStep);
        $preloadingProxy = $this->loaders->preloadingProxy($loader);
        $cachingProxy = $this->loaders->cachingProxy($preloadingProxy);
        
        $this->mappers->preload()->map(
            $preloadingProxy,
            $preloadRepository->modelName(),
            $preloadProperty->preload(),
            $preloadStep,
            $plan
        );
        
        return $this->relationship->preloader($side, $cachingProxy);
    }
    
    protected function getRepository($modelName)
    {
        return $this->models->database()->repository($modelName);
    }
    
    protected function getIdField($repository)
    {
        return $repository->config()->idField;
    }
    
    protected function loadSingleProperty($side, $related)
    {
        return $this->query($side, $related)->findOne();
    }
  
}
