<?php

namespace PHPixie\ORM\Model\Data\Document\Type;

class DocumentArray extends \PHPixie\ORM\Model\Data\Document\Type implements \ArrayAccess, \Iterator, \Countable {

    protected $originalArray;
    protected $currentArray;
    protected $reachedEnd = false;
    
    public function __construct($documentBuilder, $originalArray)
    {
        parent::__construct($documentBuilder);
        $this->originalArray = $originalArray;
        $this->currentArray = array();
        foreach($originalArray as $key => $value)
            $this->currentArray[] = $this->convertValue($value);
    }
    
    public function offsetExists($key)
    {
        return $this->handler->getEmbedded($this->model, $this->embedConfig);
    }
    
    public function offsetGet($key)
    {
        if (!$this->offsetExists($key))
            throw new \PHPixie\ORM\Exception\Model("Key '$key' doesn't exist");
    }
    
    public function offsetSet($key, $value)
    {
        if (!is_numeric($key))
            throw new \PHPixie\ORM\Exception\Model("Only numeric keys are allowed.");
        
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
    
    public function clear()
    {
        $this->currentArray = array();
    }
    
    public function currentData()
    {
        $current = array();
        foreach($this->currentArray as $key => $value)
            $current[$key] = $this->convertType($value);
        
        return $current;
    }
    
    public function push($value = null, $key)
    {
        $this->pushToCurrent($this->convertValue($value), $key);
        return $this;
    }
    
    public function last()
    {
        return $this->currentArray[$this->count()-1];
    }
    
    public function pushArray($data = array(), $key = null) {
        return $this->pushToCurrent($this->documentBuilder->documentArray($data), $key);
    }
    
    public function pushDocument($data = null, $key = null) {
        return $this->pushToCurrent($this->documentBuilder->document($data), $key);
    }
    
    protected function pushToCurrent($value, $key === null)
    {
        if ($key !== null){
            $this->currentArray[$key] = $value;
        }else
            $this->currentArray[] = $value;
        
        return $value;
    }
    
    

}