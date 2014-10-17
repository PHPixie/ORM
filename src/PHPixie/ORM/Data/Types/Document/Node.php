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
        if ($value instanceof \stdClass) {
            $value = $this->documentBuilder->document($value);
        } elseif (is_array($value)) {
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