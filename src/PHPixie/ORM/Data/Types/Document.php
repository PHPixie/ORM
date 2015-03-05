<?php

namespace PHPixie\ORM\Data\Types;

class Document extends \PHPixie\ORM\Data\Type\Implementation
{
    protected $document;

    public function __construct($document)
    {
        $this->document = $document;
    }

    public function get($key, $default = null)
    {
        return $this->document->get($key, $default);
    }
    
    public function getRequired($key)
    {
        return $this->document->getRequired($key);
    }

    protected function setValue($key, $value)
    {
        $this->document->set($key, $value);
    }

    public function data()
    {
        return $this->document->data();
    }

    public function addArray($key, $data = array())
    {
        return $this->document->addArray($key, $data);
    }

    public function addDocument($key, $data = null)
    {
        return $this->document->addDocument($key, $data);
    }
    
    public function document()
    {
        return $this->document;
    }
}
