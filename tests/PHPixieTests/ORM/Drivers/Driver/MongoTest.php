<?php

namespace PHPixieTests\ORM\Drivers\Driver;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\Mongo
 */
class MongoTest extends \PHPixieTests\ORM\Drivers\DriverTest
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
        $this->assertInstance($config, '\PHPixie\ORM\Drivers\Driver\Mongo\Config');
        
        $this->assertSame($modelName, $config->model);
    }
    
    /**
     * @covers ::repository
     * @covers ::<protected>
     */
    public function testRepository()
    {
        $config = $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Config');
        
        $repository = $this->driver->repository($config);
        $this->assertInstance($repository, '\PHPixie\ORM\Drivers\Driver\Mongo\Repository', array(
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
        $config = $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Config');
        
        $container = $this->prepareContainer();
        
        $repository = $this->driver->query($config);
        $this->assertInstance($repository, '\PHPixie\ORM\Drivers\Driver\Mongo\Query', array(
            'values'      => $this->values,
            'queryMapper' => $this->queryMapper,
            'queryMap'    => $this->queryMap,
            'container'   => $container,
            'config'      => $config
        ));
    }
    
    /**
     * @covers ::entity
     * @covers ::<protected>
     */
    public function testEntity()
    {
        $repository = $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Repository');
        $data = $this->quickMock('\PHPixie\ORM\Data\Types\Map');
        
        $entity = $this->driver->entity($repository, $data, true);
        $this->assertInstance($entity, '\PHPixie\ORM\Drivers\Driver\Mongo\Entity', array(
            'entityMap'  => $this->entityMap,
            'repository' => $repository,
            'data'       => $data,
            'isNew'      => true
        ));
    }
    
    protected function driver()
    {
        return new \PHPixie\ORM\Drivers\Driver\Mongo(
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