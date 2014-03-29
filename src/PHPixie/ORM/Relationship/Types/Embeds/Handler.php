<?php

namespace PHPixe\ORM\Relationships\Embeds;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    public function mapRelationship($side, $group, $query, $plan)
    {
        $config = $side->config();
        $this->mapper->mapConditionGroup($group->conditions, $query, $config->embeddedMap());
    }
    
	protected function getSubdocument($path, $createMissing = false)
	{
		$current = $this->owner->data();
		foreach($path as $step) {
			if (!property_exists($current, $step)) {
				if (!$createMissing)
					return null;
				$current->addSubdocument($step);
			}
			$current = $curent->$step;
		}
		
		return $current;	
	}
    
}
