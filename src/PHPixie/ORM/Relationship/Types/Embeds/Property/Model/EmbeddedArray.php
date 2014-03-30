<?php

namespace PHPixie\ORM\Relationship\Types\Embeds\Property\Model;

class EmbeddedArray extends PHPixie\ORM\Relationship\Types\Embeds\Property\Model implements \ArrayAccess, \Countable
{
    protected $models = array();
    
    public function load()
    {
        return $this->handler->arrayLoader($this, $this->models);
    }
    
    public function offsetExists($key)
    {
        if (array_key_exists($key, $this->models))
            return true;
        
        return $this->handler->arrayExistsEmbedded($this->model, $this->embedConfig, $key);
    }
    
    public function offsetGet($key)
    {
        if (array_key_exists($key, $this->models))
            return $this->models[$key];
        
        $embeddedModel = $this->handler->arrayGetEmbedded($this->model, $this->embedConfig, $key);
        $this->models[$key] = $embeddedModel;
        return $embeddedModel;
    }
    
    public function offsetSet($key, $embeddedModel)
    {
        $this->handler->arraySetEmbedded($this->model, $this->embedConfig, $key, $embeddedModel);
        $this->models[$key] = $embeddedModel;
    }
    
    public function offsetUnset($key)
    {
        $this->handler->arrayUnsetEmbedded($this->model, $this->embedConfig, $key);
        unset($this->models[$key]);
    }
    
    public function add($key = null)
    {
        $embeddedModel = $this->handler->arrayAddEmbedded($this->model, $this->embedConfig, $key);
        if($key === null){
            $this->models[$key] = $embeddedModel;
        }else {
            $this->models[] = $embeddedModel;
        }
        return $embeddedModel;
    }
    
    public function count()
    {
        return $this->handler->arrayCountEmbedded($this->model, $this->embedConfig);
    }
    
    public function clear()
    {
        return $this->handler->arrayClear($this->model, $this->embedConfig);
    }
    
    public function reset()
    {
        $this->models = array();
        parent::reset();
    }
}