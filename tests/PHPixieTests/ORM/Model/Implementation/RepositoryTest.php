<?php

namespace PHPixieTests\ORM\Model\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Implementation\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\AbstractORMTest
{
    protected $models;
    protected $dataBuilder;
    protected $repository;
    protected $modelName = 'fairy';

    public function setUp()
    {
        $this->driver = $this->quickMock('\PHPixie\ORM\Models');
        $this->dataBuilder = $this->quickMock('\PHPixie\ORM\Data');
        $this->repository = $this->repository();
    }
    
    /**
     * @covers ::modelName
     * @covers ::<protected>
     */
    public function testModelName()
    {
        $this->assertEquals($this->modelName, $this->repository->modelName());
    }
    
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $query = $this->prepareQuery();
        $this->assertEquals($query, $this->repository->query());
    }
    
    /**
     * @covers ::create
     * @covers ::<protected>
     */
    public function testCreate()
    {
        $entity = $this->prepareEntity();
        $this->assertEquals($entity, $this->repository->create());
    }
    

    protected function prepareQuery($modelsOffset = 0)
    {
        $query = $this->getQuery();
        $this->method($this->models, 'query', $query, array($this->modelName), $modelsOffset);
        return $query;
    }
    
    protected function prepareEntity($isNew = true, $modelsOffset = 0)
    {
        $entity = $this->getEntity();
        $this->method($this->models, 'entity', $entity, array($this->modelName, $isNew), $modelsOffset);
        return $entity;
    }
    
    abstract protected function repository();
    abstract protected function driver();
    abstract protected function getQuery();
    abstract protected function getEntity();
}
