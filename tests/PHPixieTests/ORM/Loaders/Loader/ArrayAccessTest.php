<?php

namespace PHPixieTests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\ArrayAccess
 */
class ArrayAccessTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    
    protected $data = array('a', 'b', 'c', 'd');
    
    /**
     * @covers ::offsetGet
     */
    public function testOffsetGet()
    {
        $this->assertEquals('b', $this->loader->getByOffset(1)); 
    }
    
    /**
     * @covers ::offsetExists
     */
    public function testOffsetExists()
    {
        $this->assertEquals(true, $this->loader->offsetExists(1)); 
        $this->assertEquals(false, $this->loader->offsetExists(99)); 
    }
    
    /**
     * @covers ::offsetGet
     */
    public function testNotFoundException()
    {
        $this->setExpectedException('\Exception');
        $this->loader->getByOffset(100);
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\ArrayAccess($this->loaders, new \ArrayObject($this->data));
    }
}