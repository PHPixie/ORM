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
        $ownerRepository = $this->getRepository($config->ownerModel);
        $ownerQuery = $ownerRepository->databaseSelectQuery();
        
        $itemRepository = $this->getRepository($config->itemModel);
        $updateQuery = $itemRepository->databaseUpdateQuery();
        
        $queryStep = $this->steps->query($updateQuery);
        $plan = $this->plans->query($queryStep);
        $requiredPlan = $plan->requiredPlan();
        
        $this->planners->in()->items(
            $ownerQuery,
            $config->ownerModel,
            $owner,
            $requiredPlan
        );
        
        $this->planners->in()->items(
            $updateQuery,
            $config->itemModel,
            $items,
            $requiredPlan
        );

        $this->planners->update()->subquery(
            $updateQuery,
            array(
                $config->ownerKey => $this->getIdField($ownerRepository)
            ),
            $ownerQuery,
            $requiredPlan
        );
        
        return $plan;
    }

    protected function getUnlinkPlan($config, $constrainOwners, $owners, $constrainItems, $items, $logic = 'and')
    {
        $itemRepository = $this->getRepository($config->itemModel);
        $updateQuery = $itemRepository->databaseUpdateQuery();
        
        $queryStep = $this->steps->query($updateQuery);
        $plan = $this->plans->query($queryStep);
        $requiredPlan = $plan->requiredPlan();
        
        $updateQuery->set($config->ownerKey, null);

        if ($constrainItems) {
            $this->planners->in()->items(
                $updateQuery,
                $config->itemModel,
                $items,
                $requiredPlan
            );
        }
        
        if ($constrainOwners) {
            $this->planners->in()->itemIds(
                $updateQuery,
                $config->ownerKey,
                $this->getRepository($config->ownerModel),
                $owners,
                $requiredPlan,
                $logic
            );
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
        
        $conditions = $collectionCondition->conditions();
        $hasConditions = !empty($conditions);
        
        $isOwner = $side->type() === 'owner';
        
        if(!$isOwner) {
            $subquery->whereNot($config->ownerKey, null);
        }
        
        if($hasConditions) {
            
            if(!$isOwner) {
                $subquery->startGroup();
            }   

            $this->mappers->conditions()->map(
                $subquery,
                $subqueryRepository->modelName(),
                $collectionCondition->conditions(),
                $plan
            );
            
            if(!$isOwner) {
                $subquery->endGroup();
            }   
        }
        
        if($isOwner) {
            $query->startConditionGroup(
                $collectionCondition->logic(),
                $collectionCondition->isNegated()
            );
            
            $query->whereNot($config->ownerKey, null);
            $subqueryLogic  = 'and';
            $negateSubquery = false;
        }else{
            $subqueryLogic  = $collectionCondition->logic();
            $negateSubquery = $collectionCondition->isNegated();
        }

        $this->planners->in()->subquery(
            $query,
            $queryField,
            $subquery,
            $subqueryField,
            $plan,
            $subqueryLogic,
            $negateSubquery
        );
        
        if($isOwner) {
            $query->endGroup();
        }
    }

    public function handleDelete($side, $reusableResult, $plan, $sidePath)
    {
        $config = $side->config();
        $itemKey = $config->ownerKey;
        $itemModel = $config->itemModel;

        $itemRepository = $this->getRepository($itemModel);
        $ownerRepository = $this->getRepository($config->ownerModel);

        $hasHandledSides = false;

        if ($config->onDelete === 'update') {
            $query = $itemRepository->databaseUpdateQuery();
            $query->set($config->ownerKey, null);
        } else {
            $deleteMapper = $this->mappers->cascadeDelete();
            $hasHandledSides = $deleteMapper->isModelHandled($itemModel);
            
            if($hasHandledSides) {
                $query = $itemRepository->databaseSelectQuery();
            }else{
                $query = $itemRepository->databaseDeleteQuery();
            }
        }
        
        $this->planners->in()->result(
            $query,
            $config->ownerKey,
            $reusableResult,
            $this->getIdField($ownerRepository),
            $plan
        );
        
        if($hasHandledSides) {
            $this->mappers->cascadeDelete()->handleQuery($query, $itemModel, $plan, $sidePath);
            
        }else{
            $step = $this->steps->query($query);
            $plan->add($step);
        }
    }

    public function mapPreload($side, $preloadProperty, $reusableResult, $plan, $relatedLoader)
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
        $preload = $preloadProperty->preload();
        $options = $preloadProperty->options();
        
        if(isset($options['queryCallback'])) {
            $callback = $options['queryCallback'];
            $callback($query);
        }
        
        $preloadStep = $this->steps->reusableResult($query);
        $plan->add($preloadStep);
        $loader = $this->loaders->reusableResult($preloadRepository, $preloadStep);
        $preloadingProxy = $this->loaders->preloadingProxy($loader);
        $cachingProxy = $this->loaders->cachingProxy($preloadingProxy);
        
        $this->mappers->preload()->map(
            $preloadingProxy,
            $preloadRepository->modelName(),
            $preload,
            $preloadStep,
            $plan,
            $cachingProxy
        );
        
        return $this->relationship->preloader(
            $side,
            $preloadRepository->config(),
            $preloadStep,
            $cachingProxy
        );
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
