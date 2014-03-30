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
            $value = $this->types->document($value);
        }elseif(is_array($value)) {
            $value = $this->types->documentArray($value);
        }
        
        return $value;
    }
    
    public function convertType($type)
    {
        if ($type instanceof Type) {
            $type = $type->currentData();
        }
        
        if (is_object($type) && !($type instanceof \stdClass))
            throw new \PHPixie\ORM\Exception\Model("Only \stdClass instances are allowed, an instance of '{get_class($type)}' passed.");
        
        
        return $type;
    }
}