<?php

namespace \PHPixie\ORM\Planners\Planner;

class Document extends \PHPixie\ORM\Planners\Planner
{
    public function getDocument($document, $key)
    {
        $document = $document->$key;
        return $this->checkDocument($document);
    }
    
    public function getArray($document, $key)
    {
        $document = $document->$key;
        return $this->checkArray($document);
    }
    
    public function addDocument($document, $key)
    {
        return $document->addDocument($key);
    }
    
    public function addArray($document, $key)
    {
        return $document->addDocumentArray($key);
    }
    
    public function setDocument($document, $key, $value)
    {
        return $document->$key = $value;
    }
    
    public function removeDocument($document, $key)
    {
        unset $document->$key;
    }
    
    public function documentExists($document, $key)
    {
        return property_exists($document, $key);
    }
    
    public function arrayGet($array, $key)
    {
        $document = $array->offsetGet($key);
        return $this->checkDocument($document);
    }
    
    public function arrayAddDocument($array, $key = null)
    {
        return $document->pushDocument(null, $key);
    }
    
    public function arraySet($array, $key, $value)
    {
        $array->offsetSet($key, $value);
    }
    
    public function arrayUnset($array, $key)
    {
        unset $array[$key];
    }
    
    public function arrayExists($array, $key)
    {
        return $array->offsetExists($key);
    }
    
    public function arrayCount($array)
    {
        return $array->count();
    }
    
    public function arrayClear($array)
    {
        return $array->clear();
    }
    
    protected function checkDocument($document)
    {
         if (!($document instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type\Document))
            return null;
        
        return $document;
    }
    
    protected function checkArray($array)
    {
         if (!($array instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type\DocumentArray))
            return null;
        
        return $array;
    }
}
