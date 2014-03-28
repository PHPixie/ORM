<?php

namespace PHPixie\ORM\Relationship\Types\Embeds;

class Model
{
    protected $subdocument;
	protected $config;
	protected $properties;
	
    public function __construct($embedsType, $subdocument, $properties)
    {
		$this->subdocument = $subdocument;
		$this->config = $config;
		$this->properties = $property;
    }
	
	public function __get($property)
	{
		$property = $this->properties->get($this, $property);
	}
	
	public function data()
	{
		return $this->subdocument;
	}
}