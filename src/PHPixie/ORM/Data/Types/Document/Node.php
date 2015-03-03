<?php

namespace PHPixie\ORM\Data\Types\Document;

abstract class Node
{
    protected $documentBuilder;

    public function __construct($documentBuilder)
    {
        $this->documentBuilder = $documentBuilder;
    }

    protected function convertValue($value)
    {
        $isArray = is_array($value);
        $isAssociativeArray = $isArray && !empty($value) && !is_numeric(key($value));
        
        if ($value instanceof \stdClass || $isAssociativeArray) {
            $value = $this->documentBuilder->document($value);
        } elseif ($isArray) {
            $value = $this->documentBuilder->arrayNode($value);
        }
        
        return $value;
    }

    protected function convertNode($type)
    {
        if ($type instanceof Node) {
            $type = $type->data();
        }elseif (is_object($type) && !($type instanceof \stdClass)){
            $class = get_class($type);
            throw new \PHPixie\ORM\Exception\Model("Only \stdClass instances are allowed, an instance of $class passed.");
        }
        
        return $type;
    }
    
    abstract public function data();
}