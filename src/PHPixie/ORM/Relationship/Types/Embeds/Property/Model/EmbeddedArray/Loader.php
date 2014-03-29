<?php

namespace PHPixie\ORM\Relationship\Types\Embeds\Property\Model\EmbeddedModel;

class Loader extends \PHPixie\ORM\Loader
{
	protected $property;
	
	public function __construct($loaders, $property)
	{
		$this->property = $property;
		parent::construct($loaders, array());
	}
	
	public function offsetExists($offset)
	{
		return $this->property->offsetExists($offset);
	}
	
    protected function getModelByOffset($offset)
	{
		return $this->property->getByOffset($offset);
	}
}