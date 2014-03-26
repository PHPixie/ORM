<?php

namespace PHPixie\ORM\Driver\Mongo\Model\Data;

abstract class Type{
	protected $types;
	
	public function __construct($types)
	{
		$this->types = $types;
	}
	
	public abstract function currentData();

	public function convertValue($value)
	{
		if ($value instanceof \stdClass) {
			$value = $this->types->subdocument($value);
		}elseif(is_array($value)) {
			$value = $this->types->subdocumentArray($value);
		}
		
		return $value;
	}
	
	public function convertType($type)
	{
		if ($type instanceof Type) {
			$type = $type->currentDatar();
		}
		
		return $type;
	}
}