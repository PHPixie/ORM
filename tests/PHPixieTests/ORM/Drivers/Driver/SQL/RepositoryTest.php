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
    protected $defaultIdField = 'id';
    
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
    public function testConfiguredTableName()
    {
        $this->config = parent::config();
        $this->method($this->config, 'get', 'pixies', array('table', null), 2);
        $repository = $this->repository();
        $this->assertSame('pixies', $repository->tableName());
    }
    
    /**
     * @covers ::tableName
     * @covers ::<protected>
     */
    public function testTableName()
    {
        $this->assertSame($this->tableName, $this->repository->tableName());
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
        return $inflector;
    }
    
    protected function config()
    {
        $config = parent::config();
        $this->method($config, 'get', NULL, array('table', null), 2);
        return $config;
    }
    
    protected function prepareSetQuerySource($query)
    {
        $this->method($query, 'table', $query, array($this->tableName), 0);
    }
    
    protected function prepareUpdateEntityData($connection, $id, $data, &$dataOffset = 0, &$connectionOffset = 0)
    {
        $query = $this->prepareDatabaseQuery('update', $connection, $connectionOffset++);

        $diff = $this->getDiff();
        $this->method($data, 'diff', $diff, array(), $dataOffset++);

        $set = array(5);
        $this->method($diff, 'set', (object) $set, array(), 0);
        $this->method($query, 'set', $query, array($set), 1);

        $this->method($query, 'where', $query, array($this->idField, $id), 2);
        $this->method($query, 'execute', null, array(), 3);
    }
    
    protected function getData()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Map');
    }
    
    protected function getDiff()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Diff');
    }

}