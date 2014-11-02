<?php

namespace PHPixieTests\ORM\Models\Type\Database\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Type\Database\Implementation\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\ORM\Models\Implementation\RepositoryTest
{
    protected $database;
    protected $config;
    
    protected $defaultIdField;
    protected $idField = 'id';
    protected $connectionName = 'test';
    
    public function setUp()
    {
        $this->database = $this->quickMock('\PHPixie\ORM\Database');
        $this->config = $this->config();
        parent::setUp();
    }
    
    /**
     * @covers ::__construct
     * @covers PHPixie\ORM\Models\Implementation\Repository::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::connectionName
     * @covers ::<protected>
     */
    public function testConnectionName()
    {
        $this->assertSame($this->connectionName, $this->repository->connectionName());
    }
    
    /**
     * @covers ::connection
     * @covers ::<protected>
     */
    public function testConnection()
    {
        $connection = $this->prepareConnection();
        $this->assertSame($connection, $this->repository->connection());
    }
    
    /**
     * @covers ::idField
     * @covers ::<protected>
     */
    public function testIdField()
    {
        $this->assertSame($this->idField, $this->repository->idField());
    }
    
    /**
     * @covers ::delete
     * @covers ::<protected>
     */
    public function testDelete()
    {
        $this->deleteTest();
        $this->deleteTest(true);
    }
    
    /**
     * @covers ::delete
     * @covers ::<protected>
     */
    public function testDeleteException()
    {
        $entity = $this->getEntity();
        $this->method($entity, 'isDeleted', true);
        $this->setExpectedException('\PHPixie\ORM\Exception\Entity');
        $this->repository->delete($entity);
    }
    
    /**
     * @covers ::save
     * @covers ::<protected>
     */
    public function testSaveException()
    {
        $entity = $this->getEntity();
        $this->method($entity, 'isDeleted', true);
        $this->setExpectedException('\PHPixie\ORM\Exception\Entity');
        $this->repository->save($entity);
    }
    
    /**
     * @covers ::databaseSelectQuery
     * @covers ::databaseUpdateQuery
     * @covers ::databaseDeleteQuery
     * @covers ::databaseInsertQuery
     * @covers ::databaseCountQuery
     * @covers ::<protected>
     */
    public function testQueries()
    {
        $types = array('select', 'update', 'delete', 'insert', 'count');
        $connection = $this->prepareConnection();
        foreach($types as $type) {
            $query = prepareDatabaseQuery($type);
            $method = 'database'.ucfirst($type).'Query';
            $this->assertSame($query, $this->repository->$method());
        }
    }
    
    protected function prepareDatabaseQuery($type)
    {
        $query = $this->getDatabaseQuery($type);
        $this->method($connection, $type.'Query', $query, array(), 0);
        $this->prepareSetQuerySource($query);
        return $query;
    }
    
    protected function deleteTest($isNew = false)
    {
        $entity = $this->getEntity();
        $this->method($entity, 'isDeleted', false);
        
        $this->method($entity, 'isNew', $isNew);
        if(!$isNew) {
            $query = $this->prepareQuery();
            $this->method($query, 'in', $query, array($entity), 0);
            $this->method($query, 'delete', null, array(), 1);
        }
        $this->method($entity, 'setIsDeleted', null, array(true), 'once');
        
        $this->repository->delete($entity);
    }
    
    public function prepareConnection()
    {
        $connection = $this->getConnection();
        $this->method($this->database, 'connection', $connection, array($this->connectionName));
        return $connection;
    }
    
    protected function config()
    {
        $config = $this->quickMock('\PHPixie\Config\Slice');
        $this->method($config, 'get', $this->connectionName, array('connection', 'default'), 0);
        $this->method($config, 'get', $this->idField, array('idField', $this->defaultIdField), 1);
        return $config;
    }
    
    abstract protected function getConnection();
    abstract protected function getDatabaseQuery($type);
    abstract protected function prepareSetQuerySource($query);
}
