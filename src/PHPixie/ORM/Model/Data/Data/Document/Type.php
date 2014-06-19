<?php

namespace PHPixie\ORM\Model\Data\Data\Document;

abstract class Type
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
            $value = $this->documentBuilder->documentArray($value);
        }

        return $value;
    }

    protected function convertType($type)
    {
        if ($type instanceof Type) {
            $type = $type->currentData();
        }

        if (is_object($type) && !($type instanceof \stdClass)){
            $class = get_class($type);
            throw new \PHPixie\ORM\Exception\Model("Only \stdClass instances are allowed, an instance of $class passed.");
        }

        return $type;
    }
    
    abstract public function currentData();
}
