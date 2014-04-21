<?php

namespace PHPixie\ORM\Reltionships\Relationship\Preloader;

abstract class Result extends \PHPixie\ORM\Reltionships\Relationship\Preloader
{
    protected $idOffsets;
    protected $mapped = false;

    public function getModel($id)
    {
        $this->ensureMapped();
        $model = $this->loader->getByOffset($this->idOffsets[$id]);
    }

    public function loadFor($property)
    {
        $this->ensureMapped();
        $property->setValue($this->getMappedFor($property->model()));
    }

    protected function ensureMapped()
    {
        if ($this->mapped)
            return;

        $this->mapItems();
        $this->mapped = true;

    }

    abstract protected function mapItems();
    abstract protected function getMappedFor($model);
}
