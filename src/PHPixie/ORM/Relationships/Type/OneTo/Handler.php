<?php

namespace PHPixie\ORM\Relationships\Type\OneTo;

abstract class Handler extends \PHPixie\ORM\Relationships\Relationship\Handler
{

    public function query($side, $related)
    {
        $config = $side->config();
        if ($side->type() !== 'owner') {
            $model = $config->itemModel;
            $property = $config->itemProperty;
        } else {
            $model = $config->ownerModel;
            $property = $config->ownerProperty;
        }

        $repository = $this->repositories->get($model);
        return $repository->query()->related($property, $related);
    }

    public function linkPlan($config, $owner, $items)
    {
        $plan = $this->plans->plan();

        $ownerRepository = $this->repositories->get($config->ownerModel);
        $ownerQuery = $onwerRepository->selectQuery();
        $this->addCollectionCondition($ownerQuery, $ownerRepository, $owner, $plan);

        $itemRepository = $this->repositories->get($config->itemModel);
        $updateQuery = $itemRepository->updateQuery();
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

    protected function addCollectionCondition($query, $repository, $plan, $items, $queryField = null)
    {
        $idField = $repository->idField();

        if($queryField === null)
            $queryField = $idField;

        $collection = $this->planners->collection($repository->modelName(), $items);
        $this->planners->in()->collection($query, $queryField, $collection, $idField, $plan);
    }

    public function unlinkPlan($config, $owner = null, $items = null)
    {
        $plan = $this->plans->plan();
        $itemRepository = $this->repositories->get($config->itemModel);
        $updateQuery = $itemRepository->updateQuery();

        if ($items !== null)
            $this->addCollectionCondition($updateQuery, $itemRepository, $items, $plan);

        if ($owner !== null) {
            $ownerRepository = $this->repositories->get($config->ownerModel);
            $ownerQuery = $ownerRepository->selectQuery();
            $this->addCollectionCondition($updateQuery, $ownerRepository, $owner, $plan, $config->ownerKey);
        }

        $updateQuery->set($config->ownerKey, null);
        return $plan;
    }

    public function mapQuery($side, $group, $query, $plan)
    {
        $config = $side->config();
        $itemRepository = $this->registryRepository->get($config->itemModel);
        $ownerRepository = $this->registryRepository->get($config->ownerModel);

        if ($side->type() !== 'owner') {
            $subqueryRepository = $ownerRepository;
            $queryField = $config->itemKey;
            $subqueryField = $ownerRepository->idField();
        } else {
            $subqueryRepository = $itemRepository;
            $queryField = $ownerRepository->idField();
            $subqueryField = $config->itemKey;
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
        return $plan;
    }

    public function preload($side, $resultLoader, $plan)
    {
        $itemRepository = $this->registryRepository->get($config->itemModel);
        $ownerRepository = $this->registryRepository->get($config->ownerModel);

        $config = $side->config();

        if ($side->type() !== 'owner') {
            $queryRepository = $ownerRepository;
            $queryField = $ownerRepository->idField();
            $resultField = $config->itemKey;
        } else {
            $queryRepository = $itemRepository;
            $queryField = $config->itemKey;
            $resultField = $ownerRepository->idField();
        }

        $query = $preloadRepository->dbQuery();
        $this->planners->in()->loader($query, $queryField, $resultLoader, $loaderField, $plan);

        $preloadStep = $this->steps->resusableResult($query);
        $preloadPlan->push($preloadStep);
        $loader = $this->loaders->reusableResult($queryRepository, $preloadStep);

        return $this->relationshipType->preloader($side, $loader);
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

        $this->planners->in()->result($query, $itemKey, $resultStep, $ownerRepository->idField());

        if ($hasHandledSides)
            $query = $this->cascadeMapper->deletion($query, $handledSides, $itemRepository, $plan);

        $deleteStep = $this->steps->query($query);
        $plan->add($deleteStep);
    }

    public function unlinkItemsPlan(){}
    public function unlinkOwnersPlan(){}
    public function removeItemsOwner(){}
    public function removeOwnerItems(){}
    public function removeAllOwnerItems(){}
}
