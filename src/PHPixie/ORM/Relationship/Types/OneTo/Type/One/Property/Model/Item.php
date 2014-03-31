<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Model;

class Item extends \PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property\Model
{

    public function set($item)
    {
        $plan = $this->handler->linkPlan($this->side->config(), $this->model, $item);
        $plan->execute();
		
		if($owner instanceof \PHPixie\ORM\Model)
			$this->processSet($item);
    }

    public function unset()
    {
        $plan = $this->handler->unlinkOwnerPlan($this->side->config(), $this->model);
        $plan->execute();
		$this->processUnset();
    }
	
	protected function opposingPropertyName()
	{
		return $this->side->config()->itemProperty;
	}
}