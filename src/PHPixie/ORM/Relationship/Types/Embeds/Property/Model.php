<?php

namespace PHPixie\ORM\Relationship\Types\Embedded\Property;

class Model extends \PHPixie\ORM\Properties\Property\Model
{
    protected $handler;
	protected $path;
	protected $embedConfig;
	
	public function __construct($handler, $side, $model, $embedConfig)
	{
		parent::construct($handler, $side, $model);
		$this->embedConfig = $embedConfig;
	}
	

}