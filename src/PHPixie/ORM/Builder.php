<?php

namespace PHPixie\ORM;

/**
 * Class Builder
 * @package PHPixie\ORM
 */
class Builder
{
    /**
     * @type \PHPixie\Database
     */
    protected $database;
    /**
     * @var \PHPixie\Slice\Type\ArrayData
     */
    protected $configSlice;
    /**
     * @var \PHPixie\ORM\Wrappers\Implementation|null
     */
    protected $wrappers;

    /**
     * @var array
     */
    protected $instances = array();

    /**
     * Builder constructor.
     * @param \PHPixie\Database $database
     * @param \PHPixie\Slice\Type\ArrayData $configSlice
     * @param \PHPixie\ORM\Wrappers\Implementation|null $wrappers
     */
    public function __construct($database, $configSlice, $wrappers = null)
    {
        $this->database    = $database;
        $this->configSlice = $configSlice;
        $this->wrappers    = $wrappers;
    }

    /**
     * @return Conditions
     */
    public function conditions()
    {
        return $this->instance('conditions');
    }

    /**
     * @return Configs
     */
    public function configs()
    {
        return $this->instance('configs');
    }

    /**
     * @return Data
     */
    public function data()
    {
        return $this->instance('data');
    }

    /**
     * @return Database
     */
    public function database()
    {
        return $this->instance('database');
    }

    /**
     * @return Drivers
     */
    public function drivers()
    {
        return $this->instance('drivers');
    }

    /**
     * @return Loaders
     */
    public function loaders()
    {
        return $this->instance('loaders');
    }

    /**
     * @return Mappers
     */
    public function mappers()
    {
        return $this->instance('mappers');
    }

    /**
     * @return Maps
     */
    public function maps() {
        return $this->instance('maps');
    }

    /**
     * @return Models
     */
    public function models()
    {
        return $this->instance('models');
    }

    /**
     * @return Planners
     */
    public function planners()
    {
        return $this->instance('planners');
    }

    /**
     * @return Plans
     */
    public function plans()
    {
        return $this->instance('plans');
    }

    /**
     * @return Relationships
     */
    public function relationships()
    {
        return $this->instance('relationships');
    }

    /**
     * @return Repositories
     */
    public function repositories()
    {
        return $this->instance('repositories');
    }

    /**
     * @return Steps
     */
    public function steps()
    {
        return $this->instance('steps');
    }

    /**
     * @return Values
     */
    public function values()
    {
        return $this->instance('values');
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method = 'build'.ucfirst($name);
            $this->instances[$name] = $this->$method();
        }
        
        return $this->instances[$name];
    }

    /**
     * @return Conditions
     */
    protected function buildConditions()
    {
        return new Conditions($this);
    }

    /**
     * @return Configs
     */
    protected function buildConfigs()
    {
        return new Configs();
    }

    /**
     * @return Data
     */
    protected function buildData()
    {
        return new Data();
    }

    /**
     * @return Database
     */
    protected function buildDatabase()
    {
        return new Database($this->database);
    }

    /**
     * @return Drivers
     */
    protected function buildDrivers()
    {
        return new Drivers($this);
    }

    /**
     * @return Loaders
     */
    protected function buildLoaders()
    {
        return new Loaders($this);
    }

    /**
     * @return Mappers
     */
    protected function buildMappers()
    {
        return new Mappers($this);
    }

    /**
     * @return Maps
     */
    protected function buildMaps() {
        return new Maps(
            $this,
            $this->configSlice->slice('relationships')
        );
    }

    /**
     * @return Models
     */
    protected function buildModels()
    {
        return new Models(
            $this,
            $this->configSlice->slice('models'),
            $this->wrappers
        );
    }

    /**
     * @return Planners
     */
    protected function buildPlanners()
    {
        return new Planners($this);
    }

    /**
     * @return Plans
     */
    protected function buildPlans()
    {
        return new Plans();
    }

    /**
     * @return Relationships
     */
    protected function buildRelationships()
    {
        return new Relationships($this);
    }

    /**
     * @return Repositories
     */
    protected function buildRepositories()
    {
        return new Repositories($this->models());
    }

    /**
     * @return Steps
     */
    protected function buildSteps()
    {
        return new Steps($this);
    }

    /**
     * @return Values
     */
    protected function buildValues()
    {
        return new Values();
    }
}
