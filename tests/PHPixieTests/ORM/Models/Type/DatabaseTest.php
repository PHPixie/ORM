<?php

namespace PHPixieTests\ORM\Models\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Database
 */
class DatabaseTest extends \PHPixieTests\ORM\Models\ModelTest
{
    protected $database;
    protected $drivers;
    protected $conditions;
    protected $mappers;
    protected $values;
    
    protected $queryMapper;
    
    protected $type = 'database';
    
    protected $wrappedRepositories = array();
    protected $wrappedEntities = array();
    protected $wrappedQueries = array();
    
    public function setUp()
    {
        $this->database   = $this->quickMock('\PHPixie\ORM\Database');
        $this->drivers    = $this->quickMock('\PHPixie\ORM\Drivers');
        $this->conditions = $this->quickMock('\PHPixie\ORM\Conditions');
        $this->mappers    = $this->quickMock('\PHPixie\ORM\Mappers');
        $this->values     = $this->quickMock('\PHPixie\ORM\Values');
        
        $this->queryMapper = $this->quickMock('\PHPixie\ORM\Mappers\Query');
        $this->method($this->mappers, 'query', $this->queryMapper, array());
        
        parent::setUp();
    }
    
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $this->queryTest(false);
        
    }
    
    protected function queryTest($isWrapped)
    {
        $config = $this->config('fairy', 'PDO');
        $driver = $this->prepareDriver('PDO');
        
        $container = $this->getConditionsContainer();
        $this->method($this->conditions, 'container', $container, array(), 0);
        
        $query  = $this->getQuery();
        $this->method($driver, 'query', $query, array(
            $this->values,
            $this->queryMapper,
            $this->relationshipMap,
            $container,
            $config
        ));
        
        $this->assertSame($query, $this->model->query($config));
    }
    
    protected function prepareBuildConfig($modelName, $slice)
    {
        $driver = $this->prepareDriver('PDO');
        $this->method($slice, 'get', 'default', array('connection', 'default'), 1);
        $this->method($this->database, 'connectionDriverName', 'PDO', array(), 0);
        
        $config = $this->getConfig();
        $this->method($driver, 'config', $config, array($this->inflector, $modelName, $slice), 0);
        return $config;
    }
    
    protected function prepareDriver($driverName)
    {
        $driver = $this->getDriver();
        $this->method($this->drivers, 'get', $driver, array($driverName), 0);
        return $driver;
    }
    
    public function config($modelName, $driverName)
    {
        $config = $this->getConfig();
        $config->model  = $modelName;
        $config->driver = $driverName;
        return $config;
    }
    
    protected function prepareWrapper()
    {
        foreach(array('repositories', 'entities', 'queries') as $key => $wrapped) {
            $method   = 'database'.ucfirst($wrapped);
            $property = 'wrapped'.ucfirst($wrapped);
            
            $this->method($this->wrapper, $method, $this->$property, array(), $key);
        }
    }
    
    protected function getConfig()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
    }
    
    protected function getQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function getConditionsContainer()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Builder\Container');
    }
    
    protected function getDriver()
    {
        return $this->abstractMock('\PHPixie\ORM\Drivers\Driver');
    }
    
    protected function model()
    {
        return new \PHPixie\ORM\Models\Type\Database(
            $this->models,
            $this->config,
            $this->relationships,
            $this->database,
            $this->drivers,
            $this->conditions,
            $this->mappers,
            $this->values
        );
    }
}