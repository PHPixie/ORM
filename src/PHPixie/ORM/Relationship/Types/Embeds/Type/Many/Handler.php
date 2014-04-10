<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Type\Many;

class Handler extends PHPixie\ORM\Relationships\Types\Embeds\Handler {
    
    public function add($embedConfig, $owner, $key = null)
    {
        $array = $this->getArray($model, $embedConfig->path, true);
        $document = $this->planners->document()->arrayAddDocument($array, $key);
        return $this->embeddedModel($embedConfig, $document);
    }

    public function get($embedConfig, $owner, $key)
    {
        $array = $this->getArray($model, $embedConfig->path);
        if ($array === null)
            return null;
        
        $document = $this->planners->document()->arrayGetDocument($array, $key);
        return $this->embeddedModel($embedConfig, $document);
    }
    
    public function exists($embedConfig, $owner, $key)
    {
        $array = $this->getArray($model, $embedConfig->path);
        if ($array === null)
            return false;
        
        return $this->planners->document()->arrayExists($array, $key);
    }
    
    public function set($embedConfig, $owner, $item, $key)
    {
        $this->checkEmbeddedClass($embedConfig, $item);
        $array = $this->getArray($owner, $embedConfig->path, true);
        $this->planners->document()->arraySet($array, $key, $embeddedModel->data()->document());
    }
    
    public function unset($embedConfig, $owner, $key)
    {
        $array = $this->getArray($owner, $embedConfig->path, true);
        $this->planners->document()->arrayUnset($array, $key);
    }
    
    public function count($embedConfig, $owner)
    {
        $array = $this->getArray($owner, $embedConfig->path);
        if ($array === null)
            return 0;
        
        return $this->planners->document()->arrayCount($array);
    }
    
    public function clear($embedConfig, $owner)
    {
        $array = $this->getArray($owner, $embedConfig->path);
        if ($array !== null)
            $this->planners->document()->arrayClear($array);
    }
    
    protected function getArray($model, $path, $createMissing = false)
    {
        $documentPlanner = $this->planners->document();
        list($parent, $key) = $this->getParentAndKey($model, $path, $createMissing);
        if ($parent === null)
            return null;
        
        $array = $documentPlanner->getArray($parent, $key);
        if ($array === null){
            if(!$createMissing)
                return null;
            $array = $documentPlanner->addArray($parent, $key);
        }
        
        return $array;
    }
}