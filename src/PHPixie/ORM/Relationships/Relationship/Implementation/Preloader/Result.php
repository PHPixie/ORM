<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Preloader;

abstract class Result extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader
{
    protected $loader;
    protected $idOffsets;
    protected $mapped = false;
    protected $side;

    public function __construct($side, $loader)
    {
        $this->loader = $loader;
        $this->side = $side;
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
        $repository = $this->loader->repository();
        $idField = $repository->config()->idField;
        
        $this->idOffsets = array_flip($this->loader->reusableResult()->getField($idField));
    }

    abstract protected function mapItems();
    abstract protected function getMappedFor($entity);
}
