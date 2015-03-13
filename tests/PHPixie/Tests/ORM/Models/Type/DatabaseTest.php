<?php

namespace PHPixie\Tests\ORM\Models\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Database
 */
class DatabaseTest extends \PHPixie\Tests\ORM\Models\ModelTest
{
    protected $database;
    protected $drivers;
    
    protected $preparedDrivers = array();
    
    protected $type = 'database';
    
    public function setUp()
    {
        $this->database   = $this->quickMock('\PHPixie\ORM\Database');
        $this->drivers    = $this->quickMock('\PHPixie\ORM\Drivers');
        parent::setUp();
    }
    
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $this->prepareWrapper('queries', array('pixie'));
        $driver = $this->prepareDriver('pdo');
        
        $this->queryTest($driver, 'fairy', false);
        $this->queryTest($driver, 'pixie', true);
    }
    
    /**
     * @covers ::entity
     * @covers ::<protected>
     */
    public function testEntity()
    {
        $this->prepareWrapper('repositories', array());
        $this->prepareWrapper('entities', array('pixie'));
        $driver = $this->prepareDriver('pdo');
        
        $this->entityTest($driver, 'fairy', false, false);
        $this->entityTest($driver, 'pixie', true, false);
    }
    
    /**
     * @covers ::repository
     * @covers ::<protected>
     */
    public function testRepository()
    {
        $this->prepareWrapper('repositories', array('pixie'));
        $driver = $this->prepareDriver('pdo');
        
        $this->repositoryTest($driver, 'fairy', false);
        $this->repositoryTest($driver, 'pixie', true);
    }
    
    /**
     * @covers ::<protected>
     */
    public function testWrappersNull()
    {
        $this->prepareNullWrappers();
        
        $driver = $this->prepareDriver('pdo');
        
        $this->entityTest($driver, 'fairy', false, false);
    }
    
    protected function queryTest($driver, $modelName, $isWrapped, $wrapperAt = 0)
    {
        $config = $config = $this->prepareConfig($modelName);
        
        $query  = $this->getQuery();
        $this->method($driver, 'query', $query, array(
            $config
        ), 1);
        
        if($isWrapped) {
            $wrapped  = $this->getQuery();
            $this->method($this->wrappers, 'databaseQueryWrapper', $wrapped, array($query), $wrapperAt);
            $query = $wrapped;
        }
        
        $this->assertSame($query, $this->model->query($modelName));
    }
    
    protected function entityTest($driver, $modelName, $isWrapped, $isNew, $driverAt = 2, $wrapperAt = 1)
    {
        $repository = $this->prepareRepository($driver, $modelName, false);
        $data = $this->getData();
        
        $config = $this->config($modelName, 'pdo');
        $this->method($repository, 'config', $config, array(), 0);
        
        $entity  = $this->getEntity();
        $this->method($driver, 'entity', $entity, array(
            $repository,
            $data,
            $isNew
        ), $driverAt);
        
        if($isWrapped) {
            $wrapped  = $this->getEntity();
            $this->method($this->wrappers, 'databaseEntityWrapper', $wrapped, array($entity));
            $entity = $wrapped;
        }
        
        $this->assertSame($entity, $this->model->entity($modelName, $data, $isNew));
    }
    
    protected function repositoryTest($driver, $modelName, $isWrapped, $wrapperAt = 0)
    {
        $repository = $this->prepareRepository($driver, $modelName, $isWrapped);
        $this->assertSame($repository, $this->model->repository($modelName));
    }
    
    protected function prepareRepository($driver, $modelName, $isWrapped, $wrapperAt = 0)
    {
        $config = $this->prepareConfig($modelName);
        $repository = $this->getRepository();
        
        $this->method($driver, 'repository', $repository, array(
            $config
        ), 1);
        
        if($isWrapped) {
            $wrapped  = $this->getRepository();
            $this->method($this->wrappers, 'databaseRepositoryWrapper', $wrapped, array($repository), $wrapperAt);
            $repository = $wrapped;
        }
        
        return $repository;
    }
    
    protected function prepareBuildConfig($modelName, $slice)
    {
        $driver = $this->prepareDriver('pdo');
        $this->method($slice, 'get', 'default', array('connection', 'default'), 1);
        $this->method($this->database, 'connectionDriverName', 'pdo', array(), 0);
        
        $config = $this->getConfig();
        $this->method($driver, 'config', $config, array($modelName, $slice), 0);
        $config->driver = 'pdo';
        
        return $config;
    }
    
    protected function prepareDriver($driverName)
    {
        if(!array_key_exists($driverName, $this->preparedDrivers)) {
            $driver = $this->getDriver();
            $this->method($this->drivers, 'get', $driver, array($driverName));
            $this->preparedDrivers[$driverName] = $driver;
        }
        return $this->preparedDrivers[$driverName];
    }
    
    public function config($modelName, $driverName)
    {
        $config = $this->getConfig();
        $config->model  = $modelName;
        $config->driver = $driverName;
        return $config;
    }
    
    protected function prepareWrapper($type, $values)
    {
        $method   = 'database'.ucfirst($type);
        $this->method($this->wrappers, $method, $values, array());
    }
    
    protected function getConfig()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
    }
    
    protected function getQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
    
    protected function getDriver()
    {
        return $this->abstractMock('\PHPixie\ORM\Drivers\Driver');
    }
    
    protected function model()
    {
        return new \PHPixie\ORM\Models\Type\Database(
            $this->models,
            $this->configs,
            $this->database,
            $this->drivers
        );
    }
}