<?php

namespace PHPixie\ORM\Data\Types\Document\Node;

class Document extends \PHPixie\ORM\Data\Types\Document\Node
{
    protected $properties = array();
    
    public function __construct($documentBuilder, $data = null)
    {
        parent::__construct($documentBuilder);
        if($data !== null) {
            foreach ($data as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    public function data()
    {
        $current = new \stdClass;
        foreach($this->properties as $key => $value)
            $current->$key = $this->convertNode($value);

        return $current;
    }

    public function set($key, $value = null)
    {
        $this->properties[$key] = $this->convertValue($value);
        return $this;
    }

    public function remove($key)
    {
        unset($this->properties[$key]);
        return $this;
    }

    public function get($key, $default = null)
    {
        if(array_key_exists($key, $this->properties))
            return $this->properties[$key];
        
        return $default;
    }
    
    public function getRequired($key)
    {
        if(array_key_exists($key, $this->properties))
            return $this->properties[$key];
        
        throw new \PHPixie\ORM\Exception\Data("Field '$key' is not set");
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
        $this->set($key, $this->convertValue($value));
    }
    
    public function __get($key)
    {
        return $this->properties[$key];
    }
    
    public function __isset($key)
    {
        return array_key_exists($key, $this->properties);
    }
    
    public function __unset($key)
    {
        $this->remove($key);
    }

}
