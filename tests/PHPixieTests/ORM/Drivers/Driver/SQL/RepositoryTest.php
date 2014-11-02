<?php

namespace PHPixieTests\ORM\Drivers\Driver\SQL;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\SQL\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\ORM\Models\Type\Database\Implementation\RepositoryTest
{
    protected $dataBuider;
    protected $inflector;
    
    protected $tableName = 'fairies';
    
    public function setUp()
    {
        $this->dataBuilder = $this->quickMock('\PHPixie\ORM\Data');
        $this->inflector = $this->inflector();
        parent::setUp();
    }
    
    /**
     * @covers ::tableName
     * @covers ::<protected>
     */
    public function testTableName()
    {
        $this->assertSame($this->tableName, $this->repository->tableName());
    }
    
    /**
     * @covers ::save
     * @covers ::<protected>
     */
    public function testSave()
    {
        $this->saveTest(true);
        $this->saveTest(false);
    }
    
    /**
     * @covers ::tableName
     * @covers ::<protected>
     */
    public function testTableName()
    {
        $this->assertSame($this->tableName, $this->repository->tableName());;
    }
    
    protected function saveTest($isNew = true)
    {
        $entity = $this->getEntity();
        $data = $this->getData();
        $this->method($entity, 'isDeleted', false, array(), 0);
        $this->method($entity, 'data', $data, array(), 1);
        
        $this->method($entity, 'isNew', $isNew, array(), 2);
        
        if($isNew) {
            $query = $this->prepareDatabaseQuery('insert');
            
            $dataArray = array(5);
            $this->method($data, 'data', (stdObject) $dataArray, array(), 0);
            $this->method($query, 'data', $query, array($dataArray), 1);
            
            $this->method($query, 'execute', null, array(), 2);
            
            $this->method($entity, 'setField', null, array($this->idField, 4), 3);
            $this->method($entity, 'setIsNew', null, array(false), 4);

        }else{
            $diff = $this->getDiff();
            $this->method($data, 'diff', $diff, array(), 0);
            
            $set = array(5);
            $this->method($diff, 'set', (stdObject) $set, array(), 0);
            $this->method($query, 'set', $query, array($set), 1);
            
            $this->method($entity, 'id', 3, array(), 3);
            
            $this->method($query, 'where', $query, array($this->idField, 3), 2);
            $this->method($query, 'execute', null, array(), 3);
        }
        
        $this->method($data, 'setCurrentAsOriginal', null, array(), 1);
        $this->repository->save($entity);
    }
    
    protected function prepareBuildData($data)
    {
        $entityData = $this->getData();
        $this->method($this->dataBuilder, 'map', $entityData, array($data), 0);
        return $entityData;
    }
    
    protected function inflector()
    {
        $inflector = $this->quickMock('\PHPixie\ORM\Inflector');
        $this->method($inflector, 'plural', $this->tableName, array($this->modelName));
    }
    
    protected function config()
    {
        $config = parent::config();
        $this->method($config, 'get', NULL, array('table', null), 2);
        return $config;
    }
    
    protected function getData()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Map');
    }
    
    protected function getDiff()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Diff');
    }
    
    protected function prepareSetQuerySource($query)
    {
        $this->method($query, 'table', $query, array($this->tableName), 0);
    }
}