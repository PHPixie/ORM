<?php

namespace PHPixieTests\ORM\Drivers\Driver;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\PDO
 */
class PDOTest extends \PHPixieTests\ORM\Drivers\DriverTest
{
    
    /**
     * @covers ::config
     * @covers ::<protected>
     */
    public function testConfig()
    {
        $modelName = 'pixie';
        $slice = $this->abstractMock('\PHPixie\Config\Slice');
        
        $config = $this->driver->config($modelName, $slice);
        $this->assertInstance($config, '\PHPixie\ORM\Drivers\Driver\PDO\Config');
        
        $this->assertSame($modelName, $config->model);
    }
    
    /**
     * @covers ::repository
     * @covers ::<protected>
     */
    public function testRepository()
    {
        $config = $this->quickMock('\PHPixie\ORM\Drivers\Driver\PDO\Config');
        
        $repository = $this->driver->repository($config);
        $this->assertInstance($repository, '\PHPixie\ORM\Drivers\Driver\PDO\Repository', array(
            'databaseModel' => $this->databaseModel,
            'database'      => $this->database,
            'dataBuilder'   => $this->data,
            'config'        => $config,
        ));
    }
    
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $config = $this->quickMock('\PHPixie\ORM\Drivers\Driver\PDO\Config');
        
        $container = $this->prepareContainer();
        
        $repository = $this->driver->query($config);
        $this->assertInstance($repository, '\PHPixie\ORM\Drivers\Driver\PDO\Query', array(
            'values'      => $this->values,
            'queryMapper' => $this->queryMapper,
            'queryMap'    => $this->queryMap,
            'container'   => $container,
            'config'      => $config
        ));
    }
    
    protected function driver()
    {
        return new \PHPixie\ORM\Drivers\Driver\PDO(
            $this->configs,
            $this->conditions,
            $this->data,
            $this->database,
            $this->models,
            $this->maps,
            $this->mappers,
            $this->values
        );
    }
}