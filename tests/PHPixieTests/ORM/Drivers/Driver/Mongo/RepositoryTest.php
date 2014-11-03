<?php

namespace PHPixieTests\ORM\Drivers\Driver\Mongo\Database;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\Mongo\database\Repository
 */
class RepositoryTest extends \PHPixieTests\ORM\Models\Type\Database\Implementation\RepositoryTest
{
    protected $dataBuider;
    protected $inflector;
    
    protected $collectionName = 'fairies';
    protected $defaultIdField = '_id';
    
    public function setUp()
    {
        $this->dataBuilder = $this->quickMock('\PHPixie\ORM\Data');
        $this->inflector = $this->inflector();
        parent::setUp();
    }
    
    /**
     * @covers ::__construct
     * @covers PHPixie\ORM\Models\Type\Database\Implementation\Repository::__construct
     * @covers PHPixie\ORM\Models\Implementation\Repository::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConfiguredCollectionName()
    {
        $this->config = parent::config();
        $this->method($this->config, 'get', 'pixies', array('collection', null), 2);
        $repository = $this->repository();
        $this->assertSame('pixies', $repository->collectionName());
    }
    
    /**
     * @covers ::collectionName
     * @covers ::<protected>
     */
    public function testCollectionName()
    {
        $this->assertSame($this->collectionName, $this->repository->collectionName());
    }
    
    protected function prepareUpdateEntityData($connection, $id, $data, &$dataOffset = 0, &$connectionOffset = 0)
    {
        $query = $this->prepareDatabaseQuery('update', $connection, $connectionOffset++);

        $diff = $this->getDiff();
        $this->method($data, 'diff', $diff, array(), $dataOffset++);

        $set = array(5);
        $this->method($diff, 'set', (object) $set, array(), 0);
        $this->method($query, 'set', $query, array($set), 1);
        
        $remove = array(6);
        $this->method($diff, 'remove', (object) $remove, array(), 1);
        $this->method($query, '_unset', $query, array($remove), 2);

        $this->method($query, 'where', $query, array($this->idField, $id), 3);
        $this->method($query, 'execute', null, array(), 4);
    }
    
    protected function prepareSetQuerySource($query)
    {
        $this->method($query, 'collection', $query, array($this->collectionName), 0);
    }
    
    protected function prepareBuildData($data)
    {
        $entityData = $this->getData();
        $this->method($this->dataBuilder, 'diffableDocument', $entityData, array($data), 0);
        return $entityData;
    }
    
    protected function inflector()
    {
        $inflector = $this->quickMock('\PHPixie\ORM\Inflector');
        $this->method($inflector, 'plural', $this->collectionName, array($this->modelName));
        return $inflector;
    }
    
    protected function config()
    {
        $config = parent::config();
        $this->method($config, 'get', NULL, array('collection', null), 2);
        return $config;
    }
    
    protected function getData()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Diffable');
    }
    
    protected function getDiff()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Diff\Removing');
    }
    
    protected function repository()
    {
        return new \PHPixie\ORM\Drivers\Driver\Mongo\Database\Repository(
            $this->models,
            $this->database,
            $this->dataBuilder,
            $this->inflector,
            $this->modelName,
            $this->config
        );
    }
    
    protected function getConnection()
    {
        return $this->quickMock('\PHPixie\Database\Driver\Mongo\Connection');
    }
    
    protected function getDatabaseQuery($type)
    {
        return $this->quickMock('\PHPixie\Database\Driver\Mongo\Query\Type\\'.ucfirst($type));
    }
    
    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Database\Query');
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Database\Entity');
    }
}