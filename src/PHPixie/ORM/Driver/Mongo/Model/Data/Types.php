<?php

namespace PHPixie\ORM\Driver\Mongo\Model\Data;

class Types{
	
	public function document($dataOject = null)
	{
		return new Type\Document($this, $dataOject);
	}
	
	public function documentArray($array = array())
	{
		return new Type\DocumentArray($this, $array);
	}
	
}