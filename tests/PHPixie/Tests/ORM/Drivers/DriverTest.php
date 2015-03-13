<?php

namespace PHPixie\Tests\ORM\Drivers;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver
 */
abstract class DriverTest extends \PHPixie\Test\Testcase
{
    protected $configs;
    protected $conditions;
    protected $data;
    protected $database;
    protected $models;
    protected $maps;
    protected $mappers;
    protected $values;
    
    protected $driver;
    
    protected $inflector;
    protected $databaseModel;
    protected $queryPropertyMapper;
    protected $queryPropertyMap;
    protected $entityPropertyMap;
    
    public function setUp()
    {
        $this->configs    = $this->quickMock('\PHPixie\ORM\Configs');
        $this->conditions = $this->quickMock('\PHPixie\ORM\Conditions');
        $this->data       = $this->quickMock('\PHPixie\ORM\Data');
        $this->database   = $this->quickMock('\PHPixie\ORM\Database');
        $this->models     = $this->quickMock('\PHPixie\ORM\Models');
        $this->maps       = $this->quickMock('\PHPixie\ORM\Maps');
        $this->mappers    = $this->quickMock('\PHPixie\ORM\Mappers');
        $this->values     = $this->quickMock('\PHPixie\ORM\Values');
        
        $this->driver = $this->driver();
        
        $this->inflector = $this->quickMock('\PHPixie\ORM\Configs\Inflector');
        $this->method($this->configs, 'inflector', $this->inflector, array());
        
        $this->databaseModel = $this->quickMock('\PHPixie\ORM\Models\Type\Database');
        $this->method($this->models, 'database', $this->databaseModel, array());
        
        $this->queryMapper = $this->quickMock('\PHPixie\ORM\Mappers\Query');
        $this->method($this->mappers, 'query', $this->queryMapper, array());

        $this->entityPropertyMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Entity');
        $this->method($this->maps, 'entityProperty', $this->entityPropertyMap, array());
        
        $this->queryPropertyMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Query');
        $this->method($this->maps, 'queryProperty', $this->queryPropertyMap, array());

    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    protected function prepareContainer($modelName)
    {
        $container = $this->quickMock('\PHPixie\ORM\Conditions\Container');
        $this->method($this->conditions, 'container', $container, array($modelName), 0);
        
        return $container;
    }
    
    abstract protected function driver();
}