<?php

namespace PHPixie\ORM;

class Configs
{
    protected $inflector;
    
    public function inflector()
    {
        if ($this->inflector === null) {
            $this->inflector = $this->buildInflector();
        }
        
        return $this->inflector;
    }
    
    protected function buildInflector()
    {
        return new \PHPixie\ORM\Configs\Inflector();
    }
}