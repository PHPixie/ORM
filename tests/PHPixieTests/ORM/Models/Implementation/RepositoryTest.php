<?php

namespace PHPixieTests\ORM\Models\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Implementation\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\AbstractORMTest
{
    protected $models;
    
    protected $repository;
    
    protected $modelName = 'fairy';
    protected $loadData;
    
    public function setUp()
    {
        $this->loadData = new \stdClass;
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->repository = $this->repository();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
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
    
    /**
     * @covers ::load
     * @covers ::<protected>
     */
    public function testLoad()
    {
        $entity = $this->prepareEntity(false, $this->loadData);
        $this->assertEquals($entity, $this->repository->load($this->loadData));
    }
    
    protected function prepareQuery($modelsOffset = 0)
    {
        $query = $this->getQuery();
        $this->method($this->models, 'query', $query, array($this->modelName), $modelsOffset);
        return $query;
    }
    
    protected function prepareEntity($isNew = true, $data = null, $modelsOffset = 0)
    {
        $entity = $this->getEntity();
        $data = $this->prepareBuildData($data);
        $this->method($this->models, 'entity', $entity, array($this->modelName, $isNew, $data), $modelsOffset);
        return $entity;
    }
    
    abstract protected function repository();
    abstract protected function getQuery();
    abstract protected function getEntity();
    abstract protected function prepareBuildData($data);
}
