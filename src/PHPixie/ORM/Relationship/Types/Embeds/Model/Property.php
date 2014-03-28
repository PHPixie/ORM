<?php

namespace PHPixie\ORM\Relationship\Types\Embeds;

class Property
{
	protected $path;
	
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
	
	public function __invoke($createMissing = false)
	{
		if (!$this->loaded)
			$this->reload($createMissing);
		
		return $this->model;
	}
	
	protected function remove()
	{
		$path = $this->path;
		$end = array_pop($path);
		
		if (empty($path)){
			$parent = $this->owner->data();
		}else
			$parent = $this->getSubdocument($path);
			if ($parent === null)
				return;
		}
		
		unset($parent->$end);
	}
}