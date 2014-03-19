<?php

namespace PHPixe\ORM\Relationships\Embedded;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    public function query($side, $related)
    {
        $config = $side->config();
        if($side->type() == 'items')
            return $this->buildQuery($config->itemModel, $related->ownerProperty, $related);

        return $this->buildQuery($config->ownerModel, $related->itemsProperty, $related);
    }

    public function moveTo($config, $owner, $items)
    {
        $itemsRepository = $this->registryRepository->get($config->itemModel);
        $ownerRepository = $this->registryRepository->get($config->ownerModel);

        $ownerCollection = $this->orm->collection($config->ownerModel);
        $ownerCollection->add($owner);

        $plan = $this->orm->plan();
        $data_query = $itemsRepository->query()->in($items);
        
        $result_step = $this->steps->result($data_query);
        $plan->push($result_step);
        $plan-
        
        
        $updatePlanner = $this->planners->update();
        
        $ownerField = $updatePlanner->field($owner, $ownerRepository->idField());
        $updatePlanner->plan(
                                $query,
                                array($config->itemKey => $ownerField),
                                $plan
                            );

        return $plan;
    }

}
