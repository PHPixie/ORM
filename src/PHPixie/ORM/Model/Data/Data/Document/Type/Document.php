<?php

namespace PHPixie\ORM\Model\Data\Document\Type;

class Document extends \PHPixie\ORM\Model\Data\Document\Type
{

    protected $originalData;
    
    public function __construct($documentBuilder, $originalData)
    {
        parent::__construct($documentBuilder);
        $this->originalData = $originalData;
        foreach($this->originalData as $key => $value) {
            $this->$key = $this->convertValue($value);
        }
    }
    
    public function currentData() {
        $currentProperties = get_object_vars($this);
        $classProperties = array_keys(get_class_vars(get_class($this->target)));
        foreach($classProperties as $property)
            unset($currentProperties[$property]);
        
        $current = new \stdClass;
        foreach($currentData as $key => $value)
            $current->$key = $this->convertType($value);
        
        return $current;
    }
    
    public function add($key, $value = null){
        $this->$target->$key = $this->convertValue($value);
        return $this;
    }
    
    public function addArray($key, $data = array()) {
        return $this->$target->$key = $this->documentBuilder->documentArray($data);
    }
    
    public function addDocument($key, $data = null) {
        return $this->$target->$key = $this->documentBuilder->document($data);
    }
    
}