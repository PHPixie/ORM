<?php

namespace PHPixie\ORM\Relationships\Relationship\Preloader;

abstract class Result extends \PHPixie\ORM\Relationships\Relationship\Preloader
{
    protected $idOffsets;
    protected $mapped = false;

    public function getModel($id)
    {
        $this->ensureMapped();
        return $this->loader->getByOffset($this->idOffsets[$id]);
    }

    public function valueFor($model)
    {
        $this->ensureMapped();
        return $this->getMappedFor($model);
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
