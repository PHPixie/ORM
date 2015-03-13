<?php

namespace PHPixie\Tests\ORM\Drivers\Driver\SQL;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\SQL\Repository
 */
abstract class RepositoryTest extends \PHPixie\Tests\ORM\Models\Type\Database\Implementation\RepositoryTest
{
    protected $dataBuider;
    
    public function setUp()
    {
        $this->dataBuilder = $this->quickMock('\PHPixie\ORM\Data');
        $this->configData['table'] = 'fairies';
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
    
    protected function prepareBuildData($data)
    {
        $entityData = $this->getData();
        $this->method($this->dataBuilder, 'map', $entityData, array($data), 0);
        return $entityData;
    }

    protected function prepareSetQuerySource($query)
    {
        $this->method($query, 'table', $query, array($this->configData['table']), 0);
    }
    
    protected function prepareUpdateEntityData($connection, $id, $data, &$dataOffset = 0, &$connectionOffset = 0)
    {
        $query = $this->prepareDatabaseQuery('update', $connection, $connectionOffset++);

        $diff = $this->getDiff();
        $this->method($data, 'diff', $diff, array(), $dataOffset++);

        $set = array(5);
        $this->method($diff, 'set', (object) $set, array(), 0);
        $this->method($query, 'set', $query, array($set), 1);

        $this->method($query, 'where', $query, array($this->configData['idField'], $id), 2);
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