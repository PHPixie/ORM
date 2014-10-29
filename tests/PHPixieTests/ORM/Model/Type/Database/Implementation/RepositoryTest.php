<?php

namespace PHPixieTests\ORM\Model\Type\Database\Implementation\;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Type\Database\Implementation\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\ORM\Model\Implementation\RepositoryTest
{
    protected $database;
    protected $connectionName;
    protected $defaultIdField;
    protected $idField = 'id';
    
    
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
        $connection = $this->getconnection();
        $this->method($this->database, 'connection', $connection);
        return $connection;
    }
    
    abstract protected function getConnection();
}
