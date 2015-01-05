<?php

namespace PHPixieTests\ORM;

class WrappersStub extends \PHPixie\ORM\Wrappers
{
    public function databaseRepositories()
    {
        return array('pixie');
    }
    
    public function databaseQueries()
    {
        return array('fairy');
    }
    
    public function databaseEntities()
    {
        return array('trixie');
    }
    
    public function embeddedEntities()
    {
        return array('blum');
    }
    
    public function pixieRepository($repository)
    {
        return $this->wrap($repository, 'databaseRepository');
    }
    
    public function fairyQuery($query)
    {
        return $this->wrap($query, 'databaseQuery');
    }
    
    public function trixieEntity($entity)
    {
        return $this->wrap($entity, 'databaseEntity');
    }
    
    public function blumEntity($entity)
    {
        return $this->wrap($entity, 'embeddedEntity');
    }
    
    protected function wrap($item, $type)
    {
        return array(
            'item' => $item,
            'type' => $type
        );
    }
}

/**
 * @coversDefaultClass \PHPixie\ORM\Wrappers
 */
class WrappersTest extends \PHPixieTests\AbstractORMTest
{
    protected $wrappers;
    
    public function setUp()
    {
        $this->wrappers = new WrappersStub();
    }
    
    /**
     * @covers ::databaseRepositoryWrapper
     * @covers ::databaseQueryWrapper
     * @covers ::databaseEntityWrapper
     * @covers ::<protected>
     */
    public function testDatabaseWrappers()
    {
        $this->wrappersTest('database', array(
            'repository' => 'pixie',
            'query'      => 'fairy',
            'entity'     => 'trixie'
        ));
    }
    
    /**
     * @covers ::embeddedEntityWrapper
     * @covers ::<protected>
     */
    public function testEmbeddedWrappers()
    {
        $this->wrappersTest('embedded', array(
            'entity' => 'blum'
        ));
    }
    
    protected function wrappersTest($modelType, $methods)
    {
        foreach($methods as $type => $modelName) {
            $method = $modelType.ucfirst($type).'Wrapper';
            $item = $this->quickMock('\PHPixie\ORM\Models\Type\\'.ucfirst($modelType).'\\'.ucfirst($type));
            $this->method($item, 'modelName', $modelName, array(), 0);
            
            $this->assertEquals(array(
                'item' => $item,
                'type' => $modelType.ucfirst($type)
            ), $this->wrappers->$method($item));
        }
    }
}























