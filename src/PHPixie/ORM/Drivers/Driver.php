<?php

namespace PHPixie\ORM\Drivers;

abstract class Driver
{
    /**
     * @type \PHPixie\ORM\Configs
     */
    protected $configs;
    /**
     * @type \PHPixie\ORM\Conditions
     */
    protected $conditions;
    /**
     * @type \PHPixie\ORM\Data
     */
    protected $data;
    /**
     * @type \PHPixie\ORM\Database
     */
    protected $database;
    /**
     * @type \PHPixie\ORM\Models
     */
    protected $models;
    /**
     * @type \PHPixie\ORM\Maps
     */
    protected $maps;
    /**
     * @type \PHPixie\ORM\Mappers
     */
    protected $mappers;
    /**
     * @type \PHPixie\ORM\Values
     */
    protected $values;
    
    public function __construct($configs, $conditions, $data, $database, $models, $maps, $mappers, $values)
    {
        $this->configs    = $configs;
        $this->conditions = $conditions;
        $this->data       = $data;
        $this->database   = $database;
        $this->models     = $models;
        $this->maps       = $maps;
        $this->mappers    = $mappers;
        $this->values     = $values;
    }
    
    abstract public function config($modelName, $configSlice);
    abstract public function repository($config);
    abstract public function query($config);
    abstract public function entity($repository, $data, $isNew);
}
