<?php

namespace PHPixie\ORM\Models\Type;

class Embedded extends \PHPixie\ORM\Models\Model
{

    protected function buildConfig($modelName, $configSlice)
    {
        return new \PHPixie\ORM\Models\Type\Embedded\Config(
            $this->configs->inflector(),
            $modelName,
            $configSlice
        );
    }
    
    public function entity($modelName, $data)
    {
        $config = $this->config($modelName);
        
        $entity = $this->buildEntity($config, $data);
        
        if($this->hasWrapper('embeddedEntities', $config->model)) {
            $entity = $this->wrappers->embeddedEntityWrapper($entity);
        }
        
        return $entity;
    }
    
    public function loadEntity()
    {
        
    }
    
    public function loadEntityFromData()
    {
        
    }
    
    protected function buildEntity($config, $data)
    {
        return new \PHPixie\ORM\Models\Type\Embedded\Implementation\Entity(
            $this->maps->entity(),
            $config,
            $data
        );
    }
    
    public function type()
    {
        return 'embedded';
    }
}