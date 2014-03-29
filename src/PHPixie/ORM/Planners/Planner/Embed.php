<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Document
{
    public function add($document, $key)
	{
		if ($document instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type\SubdocumentArray)
			return $document->pushSubdocument(null, $key);
		
		return $document->addSubdocument($key);
	}
	
    public function set($document, $key, $value)
	{
		if ($document instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type\SubdocumentArray){
			$document->offsetSet($key, $value);
		}else
			return $document->$key = $value;
	}
	
    public function remove($document, $key)
	{
		if ($document instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type\SubdocumentArray){
			unset $document->offsetUnset($key);
		}else
			unset $document->$key;
	}
	
    public function get($document, $key)
	{
		if ($document instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type\SubdocumentArray){
			$subdocument = $document->offsetGet($key);
		}else
			$subdocument = $document->$key;
		
		if (!($subdocument instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type))
			return null;
		
		return $subdocument;
	}
	
    public function exists($document, $key)
	{
		if ($document instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type\SubdocumentArray)
			return $document->offsetExists($key);
		
		return property_exists($document, $key);
	}
	
	
}
