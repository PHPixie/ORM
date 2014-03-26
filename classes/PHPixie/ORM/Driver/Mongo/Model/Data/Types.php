<?php

namespace \PHPixie\ORM\Driver\Mongo\Model\Data;

class Types{
	
	public function subdocument($dataOject = null)
	{
		return new Type\Subdocument($this, $dataOject);
	}
	
	public function subdocumentArray($array = array())
	{
		return new Type\SubdocumentArray($this, $array);
	}
	
}