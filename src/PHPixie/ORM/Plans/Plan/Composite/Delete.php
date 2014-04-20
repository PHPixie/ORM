<?php

namespace PHPixie\ORM\Plans\Plan\Composite;

class Delete extends \PHPixie\ORM\Plans\Plan\Composite
{
    protected $resultStep;
	protected $deleteStep;
	
    public function resultStep()
    {
        return $this->resultStep;
    }
	
	public function setResultStep($resultStep)
	{
		$this->resultStep = $resultStep;
	}

    public function deleteStep()
    {
        return $this->resultStep;
    }
	
	public function setDeleteStep($deleteStep)
	{
		$this->deleteStep = $deleteStep;
	}
	
    public function requiredPlan()
    {
        return $this->subplan('required');
    }
	
    public function deletePlan()
    {
        return $this->subplan('delete');
    }

	public function execute()
	{
		$this->executeSubplan('required');
		if ($this->resultStep !== null)
			$this->resultStep->execute();
		$this->executeSubplan('delete');
		$this->deleteStep->execute();
	}
	
	public function steps()
	{
		$steps = $this->subplanSteps('required');
		if ($this->resultStep !== null)
			$steps[]= $this->resultStep;
		$steps = array_merge($steps, $this->subplanSteps('delete'));
		$steps[] = $this->deleteStep;
	}
}
