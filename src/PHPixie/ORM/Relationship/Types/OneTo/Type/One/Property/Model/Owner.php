<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Model;

class Owner extends \PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Model
{

    public function set($owner)
    {
        $plan = $this->handler->linkPlan($this->side->config(), $owner, $this->model);
        $plan->execute();
		
		if($owner instanceof \PHPixie\ORM\Model)
			$this->processSet($owner);
    }

    public function unset()
    {
        $plan = $this->handler->unlinkItemPlan($this->side->config(), $this->model);
        $plan->execute();
		$this->processUnset();
    }
	
	protected function opposingPropertyName()
	{
		return $this->side->config()->ownerProperty;
	}
	
}