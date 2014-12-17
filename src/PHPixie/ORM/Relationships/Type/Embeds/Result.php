<?php

namespace PHPixie\ORM\Relationships\Type\Embeds;

class Result implements \PHPixie\Steps\Result\Reusable
{
    protected $reusableResult;
    protected $embeddedPath;
    
    public function __construct($reusableResult, $embeddedPrefix)
    {
        $this->reusableResult = $reusableResult;
        $this->embeddedPrefix = explode('.', $embeddedPrefix);
    }
    
    public function 
    public function getField($field, $skipNulls = true);
    public function getFields($fields);
    public function asArray();
    public function getByOffset($offset);
    public function offsetExists($offset);
    
    protected function getEmbeddedData($data)
}
