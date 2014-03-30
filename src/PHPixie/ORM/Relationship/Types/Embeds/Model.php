<?php

namespace PHPixie\ORM\Relationship\Types\Embeds;

class Model
{
    protected $document;
	protected $config;
	protected $properties;
	
    public function __construct($embedsType, $document, $properties)
    {
		$this->document = $document;
		$this->config = $config;
		$this->properties = $property;
    }
	
	public function __get($property)
	{
		$property = $this->properties->get($this, $property);
	}
	
	public function data()
	{
		return $this->document;
	}
}