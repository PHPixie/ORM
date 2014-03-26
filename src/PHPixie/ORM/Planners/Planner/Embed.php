<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Embed
{
    public function pushTo($owner, $relationship, $itemCollection, $itemRepository, $plan)
    {
        $pushStep = $this->steps->push($this->updateQuery($owner, $owerRepository), $itemRepository->path());
        $pullStep = $this->steps->pull($this->updateQuery($owner, $owerRepository), $itemRepository->path(), $itemRepository->idField());

        foreach ($itemCollection->addedModels() as $item) {
            $pushStep->addItem($item->fullData());
            $pullStep->addId($item->id());
        }

        foreach ($itemCollection->addedQuesries() as $query) {
            $resultPlan = $query->map();
            $resultStep->peek();
            $pushStep->addResultStep($resultStep);
            $pullStep->addResultStep($resultStep);
            $plan->prependPlan($resultPlan);
        }

        $plan->push($pullStep);
        $plan->push($pushStep);
    }

    protected function updateQuery($owner, $ownerRepository)
    {
        return $ownerRepository
                        ->query('update')
                        ->where($onwerRepository->idField(), $owner->id());
    }
}
