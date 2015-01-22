<?php

namespace PHPixie\ORM\Models\Type;

class Embedded extends \PHPixie\ORM\Models\Model
{
    protected $data;
    protected $drivers;
    protected $conditions;
    protected $mappers;
    protected $values;
    
    protected $repositories = array();
    
    public function __construct($models, $maps, $configs, $data)
    {
        $this->data = $data;
        
        parent::__construct($models, $maps, $configs);
    }
    
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
    
    public function loadEntity($modelName, $document)
    {
        $data = $this->data->document($document);
        return $this->entity($modelName, $data);
    }
    
    public function loadEntityFromData($modelName, $data)
    {
        $data = $this->data->documentFromData($data);
        return $this->entity($modelName, $data);
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