<?php

namespace PHPixe\ORM\Relationships\OneToMany;

abstract class Handler extends \PHPixie\ORM\Relationship\Type\Handler
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
        $itemRepository = $this->registryRepository->get($config->itemModel);
        $ownerRepository = $this->registryRepository->get($config->ownerModel);
        $ownerCollection = $this->planners->collection($config->ownerModel, $owner);
		
        $plan = $this->orm->plan();
        $query = $itemRepository->query()->in($items);
        $updatePlanner = $this->planners->update();
        $ownerField = $updatePlanner->field($owner, $ownerRepository->idField());
        $updatePlanner->plan(
                                $query,
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
}
