<?php

namespace \PHPixie\ORM;

abstract class Loader {
    
    protected $preloaders = array();
    
    public function load($data)
    {
    
}
    protected function preloadFor($model)
    {
        foreach($this->preloaders as $relationship => $preloader)
            $model->$relationship->setValue($preloader->loadFor($model));
    }
    
    public function addPreloader($relationship, $preloader)
    {
        $this->preloaders[$relationship] = $preloader;
    }
    
    public function getPreloader($relationship)
    {
        if (array_key_exists($relationship, $this->preloaders))
            return $this->preloaders[$relationship];
        
        return null;
    }
}