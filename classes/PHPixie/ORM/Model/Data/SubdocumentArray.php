<?php

namespace PHPixie\ORM\Model\Data;

class SubdocumentArray implements \ArrayAccess, \Iterator, \Countable {

    protected $originalArray;
    protected $currentArray;
    protected $reachedEnd = false;
    
    public function __construct($originalArray)
    {
        $this->originalArray = $originalArray;
        foreach($originalArray as $key => $value)
            $this->currentArray = $this->normalizeValue($value);
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
        $this->currentArray[$key] = $value;
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
    
    protected function normalizeValue($value)
    {
        if ($value instanceof \stdClass)
            $value = new \PHPixie\ORM\Model\Data($value);
        
        if (is_array($value))
            $value = new \PHPixie\ORM\Model\Data\SubdocumentArray($value);
        
        return $value;
    }
}