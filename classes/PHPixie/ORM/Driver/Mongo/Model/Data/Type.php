<?php

namespace \PHPixie\ORM\Driver\Mongo\Model\Data;

abstract class Type{
	protected $types;
	
	public function __construct($types)
	{
		$this->types = $types;
	}
	
	public abstract function currentData();
	public abstract function isModified();
	protected function isDataModified($newValues, $originalValues)
	{
		if (array_keys($newValues) !== array_keys($originalValues))
			return true;
		
		foreach($newValues as $key => $value){
			if ($value instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type){
				if ($value->isModified())
					return true;
			}else
				if ($value !== $originalValues[$key])
					return true;
		}
		
		return false;
	}
	
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
			$type = $type->currentData();
		}
		
		return $type;
	}
}