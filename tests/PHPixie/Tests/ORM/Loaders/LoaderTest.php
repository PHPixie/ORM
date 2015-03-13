<?php

namespace PHPixie\Tests\ORM\Loaders;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader
 */
abstract class LoaderTest extends \PHPixie\Test\Testcase
{
    protected $loaders;
    protected $loader;
    
    public function setUp()
    {
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        $this->loader = $this->getLoader();
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Loaders\Loader::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::asArray
     */
    public function testAsArray()
    {
        $entities = array();
        foreach(array(0, 1) as $i) {
            $entity = $this->getEntity();
            $this->method($entity, 'asObject', array($i), array(true), null);
            $entities[]=$entity;
        }
        
        $iterator = new \ArrayIterator($entities);
        $this->method($this->loaders, 'iterator', $iterator, array($this->loader), null);
        
        $this->assertEquals($entities, $this->loader->asArray());
        $this->assertEquals(array(array(0), array(1)), $this->loader->asArray(true));
    }
    
    /**
     * @covers ::getIterator
     */
    public function testGetIterator()
    {
        $iterator = new \ArrayIterator(array());
        $this->method($this->loaders, 'iterator', $iterator, array($this->loader), null);
        $this->assertEquals($iterator, $this->loader->getIterator());
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    abstract public function testNotFoundException();
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Model\Entity');
    }
    
    abstract protected function getLoader();
}