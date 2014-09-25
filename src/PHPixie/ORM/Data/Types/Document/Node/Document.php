<?php

namespace PHPixie\ORM\Data\Type\Document\Node;

class Document extends \PHPixie\ORM\Data\Type\Document\Node
{

    public function __construct($documentBuilder, $data = null)
    {
        parent::__construct($documentBuilder);
        if($data !== null)
            foreach ($data as $key => $value) {
                $this->$key = $this->convertValue($value);
        }
    }

    public function data()
    {
        $currentProperties = get_object_vars($this);
        $classProperties = array_keys(get_class_vars(get_class($this)));
        foreach($classProperties as $property)
            unset($currentProperties[$property]);

        $current = new \stdClass;
        foreach($currentProperties as $key => $value)
            $current->$key = $this->convertNode($value);

        return $current;
    }

    public function set($key, $value = null)
    {
        $this->$key = $value;
        return $this;
    }

    public function get($key)
    {
        return $this->$key;
    }
    
    public function addArray($key, $data = array())
    {
        return $this->$key = $this->documentBuilder->arrayNode($data);
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
