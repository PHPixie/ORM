<?php

namespace PHPixie\ORM\Model;

class Data {
	protected $data;
	protected $target;
	protected $propertiesSet = false;
	
	public function __construct($data, $target = null)
	{
		$this->data = get_object_vars($data);
		$this->target = $target !== null ? $target : $this;
	}
	
	public function setDataProperties()
	{
		foreach($this->data as $key => $value) {
			$this->target->$key = $this->normalizeValue($value);
		}
	}
	
	public function addSubdocument($key, $data = null)
	{
		if ($data === null) {
			$data = new \stdClass;
		}elseif($data instanceof static){
			$data = $data->getCurrentData()
		}elseif($data instanceof \stdClass) {
			$data = $this->buildData($data);
		}else
			throw new \PHPixie\ORM\Exception\Model("Subdocument data must be a {get_class($this)} or stdClass instance.");
		}
		$this->target->$key = $this->buildData($data);
	}
	
	public function buildData($data)
	{
		return new static($data);
	}
	
	public function getCurrentData() {
		$targetData = get_object_vars($this->target);
		$classProperties = get_class_vars(get_class($this->target));
		foreach($classProperties as $property)
			unset($targetData[$property]);
			
		$currentData = new \stdClass;
		foreach($targetData as $key => $value)
			$currentData->$key = $value;
		return $currentData;
	}
	
	public function getModified()
	{
		$old = $this->data;
		$current = $this->getCurrentData();
		
		$oldProperties = get_object_vars($old);
		$currentProperties = get_object_vars($current);
		
		$unset = a
		$set = array();
		$unset = array();
		$pull = array();
		$push = array();
		
	}
	
	public function normalizeValue($value)
	{
		if ($value !== null && $value instanceof \stdClass)
		{
			$value = $this->buildData($value);
		}
		
		return $value;
	}
	
}

$d = new Data();
$d->d=5;
$d->d=6;