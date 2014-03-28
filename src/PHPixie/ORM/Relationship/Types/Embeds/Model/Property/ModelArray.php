<?php

namespace PHPixie\ORM\Relationship\Types\Embeds\Model\Property;

class Model
{
	protected $model;
	protected $loaded = false;
	protected $embedsType;
	protected $embedConfig;
	protected $owner;
	
	public function __construct($embedsType, $embedConfig, $owner)
	{
		$this->embedsType = $embedsType;
		$this->embedConfig = $embedConfig;
		$this->owner = $owner;
	}
	
	public function reload($createMissing = false)
	{
		$this->model = null;
		$subdocument = $this->getSubdocument($this->path, $createMissing);
		if ($subdocument !== null)
			$this->embedsType->model($subdocument, $owner, $this->embedConfig)
		
		$this->loaded = true;
	}
	

	
}