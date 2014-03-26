<?php

namespace PHPixie\ORM\Driver\Mongo\Model\Data\Type;

class Subdocument extends \PHPixie\ORM\Driver\Mongo\Model\Data\Type {
    protected $data;
    protected $target;
    protected $propertiesSet = false;
    
    public function __construct($types, $data, $setProperties = true)
    {
        parent::__construct($types);
        $this->data = $data;
        if ($setProperties)
            $this->setTarget($this);
    }
    
    public function setTarget($target)
    {
        $this->target = $target;
        foreach($this->data as $key => $value) {
            $this->target->$key = $this->convertValue($value);
        }
    }
    
    public function currentData() {
        $currentData = get_object_vars($this->target);
        $classProperties = array_keys(get_class_vars(get_class($this->target)));
        foreach($classProperties as $property)
            unset($currentData[$property]);
        
        $current = new \stdClass;
        foreach($currentData as $key => $value)
            $current->$key = $this->convertType($value);
        
        return $current;
    }
    
    public function add($key, $value = null){
        $this->currentArray[] = $this->convertValue($value);
        return $this;
    }
    
    public function addArray($key, $data = array()) {
        return $this->$key = $this->types->subdocumentArray($data);
    }
    
    public function addSubdocument($key, $data = null) {
        return $this->$key = $this->types->subdocument($data);
    }
    
}