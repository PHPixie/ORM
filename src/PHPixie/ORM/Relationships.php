<?php

namespace PHPixie\ORM;

class Relationships
{
	protected $ormBuilder;
	protected $relationships = array();
	
    public function __construct($ormBuilder)
	{
		$this->ormBuilder = $ormBuilder;
	}
	
	public function get($name)
	{
		if (!array_key_exists($name, $this->relationships)) {
			$this->relationships[$name] = $this->buildRelationship($this->ormBuilder);
		}
		
		return $this->relationships[$name];
	}
	
	protected function buildRelationship($name)
	{
	    $class = '\PHPixie\ORM\Relationships\Relationship\\'.$name;
        return new $class($this);
	}
}
