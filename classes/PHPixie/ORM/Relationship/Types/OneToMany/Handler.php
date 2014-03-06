<?php

namespace PHPixe\ORM\Relationships\OneToMany;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    public function query($side, $related)
    {
        $config = $side->config();
        if($side->type() == 'items')

            return $this->buildQuery($config->itemModel, $related->ownerProperty, $related);

        return $this->buildQuery($config->ownerModel, $related->itemsProperty, $related);
    }

    public function linkPlan($config, $owner, $items)
    {
        $itemsRepository = $this->registryRepository->get($config->itemModel);
        $ownerRepository = $this->registryRepository->get($config->ownerModel);

        $ownerCollection = $this->orm->collection($config->ownerModel);
        $ownerCollection->add($owner);

        $plan = $this->orm->plan();
        $query = $itemsRepository->query()->in($items);
        $updatePlanner = $this->planners->update();
        $ownerField = $updatePlanner->field($owner, $ownerRepository->idField());
        $updatePlanner->plan(
                                $query,
                                array($config->itemKey => $ownerField),
                                $plan
                            );

        return $plan;
    }

    public function unlinkItemPlan($config, $items)
    {
        $itemsRepository = $this->registryRepository->get($config->itemModel);
        $query = $itemsRepository->query()->in($item);

        return $this->getUpdatePlan($config, $query, null);
    }

    public function unlinkOwnerPlan($config, $owner)
    {
        $itemsRepository = $this->registryRepository->get($config->itemModel);
        $query = $itemsRepository->query()
                                        ->related($config->itemProperty, $owner);

        return $this->getUpdatePlan($config, $query, null);
    }

    protected function getUpdatePlan($config, $query, $ownerId)
    {
        return $query->updatePlan(array(
                                    $config->itemKey => $ownerId
                                ));
    }

    public function mapRelationship($link, $group, $query, $plan)
    {
        $config = $link->config();
        $itemRepository = $this->registryRepository->get($config->itemModel);
        $ownerRepository = $this->registryRepository->get($config->ownerModel);
        $conditions = $group->conditions();

        if ($link->type() === 'item') {
            $subqueryRepository = $itemRepository;
            $queryField = $ownerRepository->idField();
            $subqueryField = $config->itemKey;
        } else {
            $subqueryRepository = $ownerRepository;
            $queryField = $config->itemKey;
            $subqueryField = $ownerRepository->idField();
        }

        $subquery = $subqueryRepository->query();
        $this->groupMapper->mapConditions($subquery, $conditions, $subqueryRepository->modelName(), $plan);
        $this->planners->inSubquery(
                                        $query,
                                        $queryField,
                                        $subquery,
                                        $subqueryField,
                                        $plan,
                                        $group->logic,
                                        $group->negated()
                                    );
    }

    public function preload($link, $loader, $resultStep, $resultPlan)
    {
        $config = $link->config();
        $preloadPlan = $resultPlan->preloadPlan();

        if ($link->type() === 'item') {
            $queryRepository = $itemRepository;
            $queryField = $config->itemKey;
            $resultField = $ownerRepository->idField();
        } else {
            $queryRepository = $ownerRepository;
            $queryField = $ownerRepository->idField();
            $resultField = $config->itemKey;
        }

        $query = $preloadRepository->dbQuery();

        $placeholder = $query->getWhereBuilder()->addPlaceholder();
        $inStep = $this->steps->in($placeholder, $queryField, $resultStep, $resultField);
        $preloadPlan->push($inStep);

        $preloadStep = $this->steps->result($query);

        return $preloadStep;
    }

}
