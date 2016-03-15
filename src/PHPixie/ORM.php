<?php

namespace PHPixie;

class ORM
{
    /**
     * @type ORM\Builder
     */
    protected $builder;
    
    public function __construct($database, $configSlice, $wrappers = null)
    {
        $this->builder = $this->buildBuilder($database, $configSlice, $wrappers);
    }

    /**
     * @param string $modelName
     * @return ORM\Models\Type\Database\Repository
     */
    public function repository($modelName)
    {
        return $this->databaseModel()->repository($modelName);
    }

    /**
     * @param $modelName
     * @return ORM\Models\Type\Database\Implementation\Query
     */
    public function query($modelName)
    {
        return $this->databaseModel()->query($modelName);
    }
    
    public function createEntity($modelName, $data = null)
    {
        return $this->repository($modelName)->create($data);
    }
    
    public function repositories()
    {
        return $this->builder->repositories();
    }
    
    public function builder()
    {
        return $this->builder;
    }
    
    protected function databaseModel()
    {
        return $this->builder->models()->database();
    }
    
    protected function buildBuilder($database, $configSlice, $wrappers)
    {
        return new ORM\Builder($database, $configSlice, $wrappers);
    }
}
