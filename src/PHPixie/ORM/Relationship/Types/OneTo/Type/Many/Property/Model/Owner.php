<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model;

class Owner extends \PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model
{
	
    public function set($owner)
    {
		$config = $this->side->config();
        $plan = $this->handler->linkPlan($config, $owner, $this->model);
        $plan->execute();
		
		$this->resetCurrentOwner();
		$this->resetOwner($owner);
		
		$this->setValue($owner);
    }

    public function unset()
    {
        $plan = $this->handler->unlinkItemPlan($this->side->config(), $this->model);
        $plan->execute();
		
		$this->resetCurrentOwner();
	
		$this->setValue(null);
    }
	
	public function resetCurrentOwner()
	{
		if ($this->loaded && $this->value !== null)
			$this->resetOwner($this->value);
	}
	
	protected function resetOwner($owner)
	{
		$owner = $this->value;
		$ownerProperty = $config->ownerProperty;
		$owner->$ownerProperty->reset();
	}
}