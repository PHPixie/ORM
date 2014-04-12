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
        $this->handler->setOwnerProperty($this->embedConfig, $embeddedModel, $this->model);
        $this->models[$key] = $embeddedModel;
    }
    
    public function offsetUnset($key)
    {
        $this->handler->arrayUnsetEmbedded($this->model, $this->embedConfig, $key);
        $this->handler->unsetOwnerProperty($this->embedConfig, $this->models[$key], null);
        unset($this->models[$key]);
    }
    
    public function add($key = null)
    {
        $embeddedModel = $this->handler->arrayAddEmbedded($this->model, $this->embedConfig, $key);
        $this->handler->setOwnerProperty($this->embedConfig, $embeddedModel, $this->model);
        
        if($key === null){
            $this->models[$key] = $embeddedModel;
        }else {
            $this->models[] = $embeddedModel;
        }
        return $embeddedModel;
    }
    
    public function remove($models)
    {
        if (!is_array($models))
            $models = array($models);
        
        while (!empty($models)) {
            $model = array_pop($models);
            $key = array_search($model, $this->models, true);
            if ($id === false)
                throw new \PHPixie\ORM\Exception\Model("The model to be removed was not found.");
            $this->offsetUnset($key);
        }
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