<?php

namespace PHPixie\Tests\ORM\Wrappers;

class ImplementationStub extends \PHPixie\ORM\Wrappers\Implementation
{
    protected $databaseRepositories = array('pixie');
    protected $databaseQueries      = array('fairy');
    protected $databaseEntities     = array('trixie');
    protected $embeddedEntities     = array('blum');
    
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
 * @coversDefaultClass \PHPixie\ORM\Wrappers\Implementation
 */
class ImplementationTest extends \PHPixie\Test\Testcase
{
    protected $wrappers;
    
    public function setUp()
    {
        $this->wrappers = new ImplementationStub();
    }

    /**
     * @covers ::databaseRepositories
     * @covers ::databaseQueries
     * @covers ::databaseEntities
     * @covers ::embeddedEntities
     * @covers ::<protected>
     */
    public function testDefaultWrappers()
    {
        $methods = array(
            'databaseRepositories',
            'databaseQueries',
            'databaseEntities',
            'embeddedEntities'
        );
        
        $wrappers = new \PHPixie\ORM\Wrappers\Implementation();
        
        foreach($methods as $method) {
            $this->assertSame(array(), $wrappers->$method());
        }
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