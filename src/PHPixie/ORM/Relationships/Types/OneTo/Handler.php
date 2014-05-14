<?php

namespace PHPixie\ORM\Relationships\Types\OneTo;

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

        return $this->registryRepository->get($model)->related($property, $related);
    }

            return $this->buildQuery($config->itemModel, $related->ownerProperty, $related);

        return $this->buildQuery($config->ownerModel, $related->itemsProperty, $related);
    }

    public function linkPlan($config, $owner, $items)
    {
        $itemRepository = $this->registryRepository->get($config->itemModel);
        $ownerRepository = $this->registryRepository->get($config->ownerModel);
        $ownerCollection = $this->planners->collection($config->ownerModel, $owner);

        $plan = $this->orm->plan();
        $itemQuery = $itemRepository->query()->in($items);
        $updatePlanner = $this->planners->update();
        $ownerField = $updatePlanner->field($owner, $ownerRepository->idField());
        $updatePlanner->plan(
                                $itemQuery,
                                array($config->itemKey => $ownerField),
                                $plan
                            );

        return $plan;
    }

    public function unlinkPlan($config, $owner = null, $items = null)
    {
        $query = $this->registryRepository->get($config->itemModel)->query();

        if ($items !== null)
            $query->in($items);

        if ($owner !== null)
            $query->related($config->itemProperty, $owner);

        return $query->planUpdate(array($config->itemKey => null));
    }

    public function mapRelationship($side, $group, $query, $plan)
    {
        $config = $side->config();
        $itemRepository = $this->registryRepository->get($config->itemModel);
        $ownerRepository = $this->registryRepository->get($config->ownerModel);
        $conditions = $group->conditions();

        if ($side-> type() !== 'owner') {
            $subqueryRepository = $ownerRepository;
            $queryField = $config->itemKey;
            $subqueryField = $ownerRepository->idField();
        } else {
            $subqueryRepository = $itemRepository;
            $queryField = $ownerRepository->idField();
            $subqueryField = $config->itemKey;
        }

        $subquery = $subqueryRepository->query();
        $this->groupMapper->mapConditions($subquery, $conditions, $subqueryRepository->modelName(), $plan);
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
            $handledSides = $this->cascadeMapper=>deletionSides($itemModel);
            $hasHandledSides = !empty($handlesSides);
            $query = $repository->databaseQuery($hasHandledSides ? 'select' : 'delete');
        }

        $this->planners->in()->result($query, $itemKey, $resultStep, $ownerRepository->idField());

        if ($hasHandledSides)
            $query = $this->cascadeMapper->deletion($query, $handledSides, $itemRepository, $plan)

        $deleteStep = $this->steps->query($query);
        $plan->add($deleteStep);
    }
}
