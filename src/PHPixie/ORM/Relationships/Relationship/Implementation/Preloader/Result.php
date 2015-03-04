<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Preloader;

abstract class Result extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader
{
    protected $side;
    protected $modelConfig;
    protected $result;
    protected $loader;
    
    protected $idOffsets;
    protected $mapped = false;
    

    public function __construct($side, $modelConfig, $result, $loader)
    {
        $this->side = $side;
        $this->modelConfig = $modelConfig;
        $this->result = $result;
        $this->loader = $loader;
    }

    public function getEntity($id)
    {
        $this->ensureMapped();
        return $this->loader->getByOffset($this->idOffsets[$id]);
    }

    public function loadProperty($property)
    {
        $this->ensureMapped();
        $entity = $property->entity();
        $property->setValue($this->getMappedFor($entity));
    }

    protected function ensureMapped()
    {
        if ($this->mapped)
            return;

        $this->mapItems();
        $this->mapped = true;

    }

    protected function mapIdOffsets()
    {
        $idField = $this->modelConfig->idField;
        $this->idOffsets = array_flip($this->result->getField($idField));
    }

    abstract protected function mapItems();
    abstract protected function getMappedFor($entity);
}
