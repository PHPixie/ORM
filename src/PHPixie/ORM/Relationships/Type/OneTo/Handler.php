<?php

namespace PHPixie\ORM\Relationships\Type\OneTo;

abstract class Handler extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
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

        $repository = $this->repositories->get($model);
        return $repository->query()->relatedTo($property, $related);
    }

    public function linkPlan($config, $owner, $items)
    {
        $plan = $this->plans->steps();

        $ownerRepository = $this->repositories->get($config->ownerModel);
        $ownerQuery = $ownerRepository->databaseSelectQuery();
        
        $this->addCollectionCondition($ownerQuery, $ownerRepository, $owner, $plan);

        $itemRepository = $this->repositories->get($config->itemModel);
        $updateQuery = $itemRepository->databaseUpdateQuery();
        
        $this->addCollectionCondition($updateQuery, $itemRepository, $items, $plan);

        $this->planners->update()->subquery(
                                                $updateQuery,
                                                array(
                                                    $config->ownerKey => $ownerRepository->idField()
                                                ),
                                                $ownerQuery,
                                                $plan
                                            );
        return $plan;
    }

    protected function addCollectionCondition($query, $repository, $items, $plan, $queryField = null, $logic = 'and')
    {
        $idField = $repository->config->idField();
        if($queryField === null)
            $queryField = $idField;
        
        $collection = $this->planners->collection($repository->modelName(), $items);
        $this->planners->in()->collection($query, $queryField, $collection, $idField, $plan, $logic);
    }

    protected function getUnlinkPlan($config, $constrainOwners, $owners, $constrainItems, $items, $logic = 'and')
    {
        $plan = $this->plans->steps();

        $itemRepository = $this->repositories->get($config->itemModel);
        $updateQuery = $itemRepository->databaseUpdateQuery();
        $updateQuery->set($config->ownerKey, null);

        if ($constrainItems)
            $this->addCollectionCondition($updateQuery, $itemRepository, $items, $plan);

        if ($constrainOwners) {
            $ownerRepository = $this->repositories->get($config->ownerModel);
            $this->addCollectionCondition($updateQuery, $ownerRepository, $owners, $plan, $config->ownerKey, $logic);
        }


        return $plan;
    }

    public function mapQuery($side, $group, $query, $plan)
    {
        $config = $side->config();
        $itemRepository = $this->repositories->get($config->itemModel);
        $ownerRepository = $this->repositories->get($config->ownerModel);

        if ($side->type() === 'owner') {
            $subqueryRepository = $ownerRepository;
            $queryField = $config->ownerKey;
            $subqueryField = $ownerRepository->idField();
        } else {
            $subqueryRepository = $itemRepository;
            $queryField = $ownerRepository->idField();
            $subqueryField = $config->ownerKey;
        }

        $subquery = $subqueryRepository->databaseSelectQuery();
        $this->groupMapper->mapConditions(
                                            $subquery,
                                            $group->conditions(),
                                            $subqueryRepository->modelName(),
                                            $plan
                                        );

        $this->planners->in()->subquery(
                                        $query,
                                        $queryField,
                                        $subquery,
                                        $subqueryField,
                                        $plan,
                                        $group->logic(),
                                        $group->negated()
                                    );
    }

    public function handleDeletion($modelName, $side, $resultStep, $plan)
    {
        if ($side->type() === 'owner')
            return;

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
        
        $this->planners->in()->result($query, $itemKey, $resultStep, $ownerRepository->config()->idField());

        if ($hasHandledSides)
            $query = $this->cascadeMapper->deletion($query, $handledSides, $itemRepository, $plan);

        $deleteStep = $this->steps->query($query);
        $plan->add($deleteStep);
    }

    public function mapPreload($side, $preloadProperty, $reusableResult, $plan)
    {
        $config = $side->config();

        $itemRepository = $this->repositories->get($config->itemModel);
        $ownerRepository = $this->repositories->get($config->ownerModel);

        if ($side->type() === 'owner') {
            $preloadRepository = $ownerRepository;
            $queryField = $ownerRepository->idField();
            $resultField = $config->ownerKey;
        } else {
            $preloadRepository = $itemRepository;
            $queryField = $config->ownerKey;
            $resultField = $ownerRepository->idField();
        }

        $query = $preloadRepository->databaseSelectQuery();
        $this->planners->in()->result(
                                        $query,
                                        $queryField,
                                        $resultStepLoader->reusableResult(),
                                        $resultField,
                                        $preloadPlan
                                    );

        $preloadStep = $this->steps->reusableResult($query);
        $preloadPlan->add($preloadStep);
        $loader = $this->loaders->reusableResult($preloadRepository, $preloadStep);
        return $this->relationship->preloader($side, $loader);
    }
    
    protected function loadSingleProperty($side, $related)
    {
        return $this->query($side, $related)->findOne();
    }
  
}
