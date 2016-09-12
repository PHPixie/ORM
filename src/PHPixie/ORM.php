<?php

namespace PHPixie;

/**
 * Class ORM
 * @package PHPixie
 */
class ORM
{
    /**
     * @type ORM\Builder
     */
    protected $builder;

    /**
     * ORM constructor.
     * @param \PHPixie\Database $database
     * @param \PHPixie\Slice\Type\ArrayData $configSlice
     * @param \PHPixie\ORM\Wrappers\Implementation|null $wrappers
     */
    public function __construct($database, $configSlice, $wrappers = null)
    {
        $this->builder = $this->buildBuilder($database, $configSlice, $wrappers);
    }

    /**
     * @param string $modelName
     * @return \PHPixie\ORM\Models\Type\Database\Repository
     */
    public function repository($modelName)
    {
        return $this->databaseModel()->repository($modelName);
    }

    /**
     * @param string $modelName
     * @return \PHPixie\ORM\Models\Type\Database\Implementation\Query
     */
    public function query($modelName)
    {
        return $this->databaseModel()->query($modelName);
    }

    /**
     * @param string $modelName
     * @param null $data
     * @return mixed
     */
    public function createEntity($modelName, $data = null)
    {
        return $this->repository($modelName)->create($data);
    }

    /**
     * @return \PHPixie\ORM\Repositories
     */
    public function repositories()
    {
        return $this->builder->repositories();
    }

    /**
     * @return \PHPixie\ORM\Builder
     */
    public function builder()
    {
        return $this->builder;
    }

    /**
     * @return \PHPixie\ORM\Models\Type\Database
     */
    protected function databaseModel()
    {
        return $this->builder->models()->database();
    }

    /**
     * @param \PHPixie\Database $database
     * @param \PHPixie\Slice\Type\ArrayData $configSlice
     * @param \PHPixie\ORM\Wrappers\Implementation|null $wrappers
     * @return \PHPixie\ORM\Builder
     */
    protected function buildBuilder($database, $configSlice, $wrappers)
    {
        return new ORM\Builder($database, $configSlice, $wrappers);
    }
}
