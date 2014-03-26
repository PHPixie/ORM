<?php

namespace PHPixie\ORM\Driver\Mongo\Model\Data\Type;

class SubdocumentArray extends \PHPixie\ORM\Driver\Mongo\Model\Data\Type implements \ArrayAccess, \Iterator, \Countable {

    protected $originalArray;
    protected $currentArray;
    protected $reachedEnd = false;
    
    public function __construct($types, $originalArray)
    {
        parent::__construct($types);
        $this->originalArray = $originalArray;
        $this->currentArray = array();
        foreach($originalArray as $key => $value)
            $this->currentArray[] = $this->convertValue($value);
    }
    
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->currentArray);
    }
    
    public function offsetGet($key)
    {
        if (!$this->offsetExists($key))
            throw new \PHPixie\ORM\Exception\Model("Key '$key' doesn't exist");
    }
    
    public function offsetSet($key, $value)
    {
        $this->currentArray[$key] = $this->convertValue($value);
    }
    
    public function offsetUnset($key)
    {
        if ($this->offsetExists($key))
            unset($this->currentArray[$key]);
    }
    
    public function current()
    {
        return current($this->currentArray);
    }
    
    public function key()
    {
        return key($this->currentArray);
    }
    
    public function next()
    {
        next($this->currentArray);
        $this->reachedEnd = $this->key() === $this->count() - 1;
    }
    
    public function rewind()
    {
        reset($this->currentArray);
        $this->reachedEnd = false;
    }
    
    public function valid()
    {
        return !$this->reachedEnd;
    }
    
    public function count()
    {
        return count($this->currentArray);
    }
    
    public function currentData()
    {
        $current = array();
        foreach($this->currentArray as $key => $value)
            $current[$key] = $this->convertType($value);
        
        return $current;
    }
    
    public function push($value = null)
    {
        $this->currentArray[] = $this->convertValue($value);
        return $this;
    }
    
    public function last()
    {
        return $this->currentArray[$this->count()-1];
    }
    
    public function pushArray($data = array()) {
        return $this->currentArray[]= $this->types->subdocumentArray($data);
    }
    
    public function pushSubdocument($data = null) {
        return $this->currentArray[]= $this->types->subdocument($data);
    }

}