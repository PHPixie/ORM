<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model;

class Items extends \PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model {


    public function load()
    {
        return $this->query()->findAll();
    }
	
    public function add($items)
    {
        $plan = $this->handler->linkPlan($this->side->config(), $this->model, $items);
        $plan->execute();
		$this->processItemsOwner($items, $this->model);
		$this->reset();
    }

    public function remove($items)
    {
        $plan = $this->handler->unlinkItemsPlan($this->side->config(), $items, $this->model);
        $plan->execute();
		$this->processItemsOwner($items, null);
		$this->reset();
	}
	
    public function removeAll()
    {
        $plan = $this->handler->unlinkOwnerPlan($this->side->config(), $this->model);
        $plan->execute();
		if ($this->loaded && $this->value !== null)
			$this->processItemsOwner($this->value, null);
		
		$this->reset();
	}
	
	protected function processItemsOwner($items, $owner)
	{
		$ownerPropertyName = $this->side-> config()->itemProperty;
		
		foreach($items as $item) {
			if (!($item instanceof \PHPixie\ORM\Model))
				continue;
				
			if (!$ownerProperty->loaded() || $ownerProperty->value() === $owner)
				continue;
			
			$ownerProperty->resetCurrentOwner();
			$ownerProperty->setValue($owner);
		}
	}
	
}