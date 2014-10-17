<?php

namespace PHPixie\ORM\Data\Types\Document\Node;

class ArrayNode extends \PHPixie\ORM\Data\Types\Document\Node implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $currentArray;

    public function __construct($documentBuilder, $array)
    {
        parent::__construct($documentBuilder);
        $this->currentArray = array();
        foreach($array as $value)
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
        if($key > $this->count()) {
            throw new \PHPixie\ORM\Exception\Data("Document arrays may not have gaps. Key $key is larger than array count {$this->count()}.");
        }
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

    public function data()
    {
        $current = array();
        foreach($this->currentArray as $key => $value)
            $current[$key] = $this->convertNode($value);

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
        return $this->appendToCurrent($this->documentBuilder->arrayNode($data), $key);
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
