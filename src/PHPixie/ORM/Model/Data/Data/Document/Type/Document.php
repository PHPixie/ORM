<?php

namespace PHPixie\ORM\Model\Data\Data\Document\Type;

class Document extends \PHPixie\ORM\Model\Data\Data\Document\Type
{

    protected $originalData;

    public function __construct($documentBuilder, $originalData)
    {
        parent::__construct($documentBuilder);
        $this->originalData = $originalData;
        foreach ($this->originalData as $key => $value) {
            $this->$key = $this->convertValue($value);
        }
    }

    public function currentData()
    {
        $currentProperties = get_object_vars($this);
        $classProperties = array_keys(get_class_vars(get_class($this)));
        foreach($classProperties as $property)
            unset($currentProperties[$property]);

        $current = new \stdClass;
        foreach($currentProperties as $key => $value)
            $current->$key = $this->convertType($value);

        return $current;
    }

    public function set($key, $value = null)
    {
        $this->$key = $value;
        return $this;
    }

    public function addArray($key, $data = array())
    {
        return $this->$key = $this->documentBuilder->documentArray($data);
    }

    public function addDocument($key, $data = null)
    {
        return $this->$key = $this->documentBuilder->document($data);
    }

    public function __set($key, $value)
    {
        $this->$key = $this->convertValue($value);
    }
}
