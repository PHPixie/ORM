<?php

namespace PHPixie\Tests\ORM\Drivers\Driver\Mongo;

class Id
{
    protected $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function __toString()
    {
        return $this->id;
    }
}

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\Mongo\Repository
 */
class RepositoryTest extends \PHPixie\Tests\ORM\Models\Type\Database\Implementation\RepositoryTest
{
    protected $dataBuilder;
    protected $collectionName = 'fairies';
    
    public function setUp()
    {
        $this->dataBuilder = $this->quickMock('\PHPixie\ORM\Data');
        $this->configData['collection'] = 'fairies';
        parent::setUp();
    }
    
    /**
     * @covers ::__construct
     * @covers PHPixie\ORM\Models\Type\Database\Implementation\Repository::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::load
     * @covers ::<protected>
     */
    public function testLoadMongoId()
    {
        $loadData = clone $this->loadData;
        
        $this->loadData->_id = 'test';
        $loadData->_id = new Id('test');
        
        $entity = $this->prepareEntity($this->loadData, false);
        $this->assertSame($entity, $this->repository->load($this->loadData));
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

        $this->method($query, 'where', $query, array($this->configData['idField'], $id), 3);
        $this->method($query, 'execute', null, array(), 4);
    }
    
    protected function prepareSetQuerySource($query)
    {
        $this->method($query, 'collection', $query, array($this->collectionName), 0);
    }
    
    protected function prepareBuildData($data)
    {
        $entityData = $this->getData();
        $this->method($this->dataBuilder, 'diffableDocumentFromData', $entityData, array($data), 0);
        return $entityData;
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
        return new \PHPixie\ORM\Drivers\Driver\Mongo\Repository(
            $this->databaseModel,
            $this->database,
            $this->dataBuilder,
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
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Query');
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Entity');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Config');
    }
}