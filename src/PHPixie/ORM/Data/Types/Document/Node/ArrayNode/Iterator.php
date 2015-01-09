<?php

namespace PHPixie\ORM\Data\Types\Document\Node\ArrayNode;

class Iterator implements \Iterator
{
    protected $documentArray;
    protected $key = 0;
    
    public function __construct($documentArray)
    {
        $this->documentArray = $documentArray;
    }
    
    public function current()
    {
        return $this->documentArray->offsetGet($this->key);
    }
    
    public function next()
    {
        if($this->valid())
            $this->key++;
    }
    
    public function key()
    {
        return $this->key;
    }
    
    public function rewind()
    {
        $this->key = 0;
    }
    
    public function valid()
    {
        return $this->key < $this->documentArray->count();
    }

}