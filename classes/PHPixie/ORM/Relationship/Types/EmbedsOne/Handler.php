<?php

namespace PHPixe\ORM\Relationships\Embeds;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    public function mapRelationship($side, $group, $query, $plan)
    {
        $config = $side->config();
        $conditions = $group->conditions();

        if ($side->type() === 'item') {
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
    
    protected function mapConditions($query, $conditions,
}
