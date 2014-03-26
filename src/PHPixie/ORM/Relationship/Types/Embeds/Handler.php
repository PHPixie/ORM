<?php

namespace PHPixe\ORM\Relationships\Embeds;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    public function mapRelationship($side, $group, $query, $plan)
    {
        $config = $side->config();
		$this->mapper->mapConditionGroup($group->conditions, $query, $config->embeddedMap());
    }
    
}
