<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Preload;

abstract class Result implements \PHPixie\ORM\Steps\Result\Reusable
{
    protected $reusableResult;
    protected $embeddedPrefix;
    
    protected $data;

    public function __construct($reusableResult, $embeddedPrefix)
    {
        $this->reusableResult = $reusableResult;
        $this->embeddedPrefix = $embeddedPrefix;
    }

    public function offsetExists($offset)
    {
        $this->requireData();
        return array_key_exists($offset, $this->data);
    }

    public function getByOffset($offset)
    {
        $this->requireData();
        return $this->data[$offset];
    }

    protected function requireData()
    {
        if($this->data !== null)
            return;
        
        $this->prepareData();
    }

    protected function getEmbeddedData($data, $path)
    {
        if($data === null) 
            return null;
        
        foreach($path as $step) {
            if(!property_exists($data, $step))
                return null;
            $data = $data->$step;
        }
        
        return $data;
    }
    
    public function getField($field, $skipNulls = true)
    {
        $this->requireData();
        $values = array();
        $path = explode('.', $field);
        
        foreach($this->data as $data) {
            $value = $this->getEmbeddedData($row, $path);
            if($value !== null || !$skipNulls) {
                $values[] = $value;
            }
        }
        
        return $values;
    }
    
    public function getFields($fields)
    {
        $this->requireData();
        $rows = array();
        $paths = array();
        
        foreach($fields as $field) {
            $paths[$field] = explode('.', $field);
        }
        
        foreach($this->data as $data) {
            $row = array();
            foreach($paths as $field => $path) {
                $row[$field] = $this->getEmbeddedData($data, $path);
            }
            
            $rows[]= $row;
        }
        
        return $rows;
        
    }
    
    public function getIterator()
    {
        $this->requireData();
        return new \ArrayIterator($this->data);
    }
    
    public function asArray()
    {
        $this->requireData();
        return $this->data;
    }
    
    abstract protected function prepareData();
}
