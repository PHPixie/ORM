<?php

namespace PHPixie\ORM\Relationships\Relationship\Preloader;

abstract class Result extends \PHPixie\ORM\Relationships\Relationship\Preloader
{
    protected $idOffsets;
    protected $mapped = false;
    protected $side;
    
    public function __construct($side, $loader)
    {
        parent::__construct($loader);
        $this->side = $side;
    }
    
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
    
    protected function mapIdOffsets()
    {
        $idField = $this->loader->repository()->idField();
        $ids = $this->loader->reusableResult()->getField($idField);
        foreach ($ids as $offset => $id) {
            $this->idOffsets[$id] = $offset;
        }
    }

    abstract protected function mapItems();
    abstract protected function getMappedFor($model);
}
