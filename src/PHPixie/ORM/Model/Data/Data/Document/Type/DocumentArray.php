<?php

namespace PHPixie\ORM\Model\Data\Data\Document\Type;

class DocumentArray extends \PHPixie\ORM\Model\Data\Data\Document\Type implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $currentArray;

    public function __construct($documentBuilder, $originalArray)
    {
        parent::__construct($documentBuilder);
        $this->currentArray = array();
        foreach($originalArray as $value)
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
        return $this->currentArray[$key];
    }

    public function offsetSet($key, $value)
    {
        $this->appendToCurrent($this->convertValue($value), $key);
    }

    public function offsetUnset($key)
    {
        array_splice($this->currentArray, $key, 1);
    }

    public function count()
    {
        return count($this->currentArray);
    }

    public function getIterator()
    {
        return $this->documentBuilder->arrayIterator($this);
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

    public function append($value = null)
    {
        $this->appendToCurrent($this->convertValue($value));
        return $this;
    }

    public function last()
    {
        return $this->currentArray[$this->count()-1];
    }

    public function appendArray($data = array(), $key = null)
    {
        return $this->appendToCurrent($this->documentBuilder->documentArray($data), $key);
    }

    public function appendDocument($data = null, $key = null)
    {
        return $this->appendToCurrent($this->documentBuilder->document($data), $key);
    }

    protected function appendToCurrent($value, $key = null)
    {
        if($key === null){
            $this->currentArray[] = $value;
            
        }else{
            if (!is_numeric($key))
                throw new \PHPixie\ORM\Exception\Model("Only numeric keys can be used.");
            
            $count = $this->count();
            
            if($key > $count)
                throw new \PHPixie\ORM\Exception\Model("Only sequential keys can be used.");
            
            $this->currentArray[$key] = $value;
        }
        
        return $value;
    }

}
