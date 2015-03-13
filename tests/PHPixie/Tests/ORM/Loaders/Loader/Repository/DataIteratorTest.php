<?php

namespace PHPixie\Tests\ORM\Loaders\Loader\Repository;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository\DataIterator
 */
class DataIteratorTest extends \PHPixie\Tests\ORM\Loaders\Loader\RepositoryTest
{
    protected $data = array();
    protected $entities = array();
    protected $iterator;
    
    public function setUp()
    {
        foreach(range(0,4) as $i) {
            $this->data[] = new \stdClass;
            $this->entities[] = $this->getEntity();
        }
        
        parent::setUp();
    }
        
    /**
     * @covers ::dataIterator
     */
    public function testDataIterator()
    {
        $this->assertEquals($this->iterator, $this->loader->dataIterator());
    }
    
    /**
     * @covers ::offsetExists
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testOffsetExists()
    {
        foreach(range(0, 4) as $i) {
            $this->assertSame(true, $this->loader->offsetExists($i));
            $this->assertSame(true, $this->loader->offsetExists($i));
            $entity = $this->prepareLoadEntity($this->data[$i]);
            $this->assertSame($entity, $this->loader->getByOffset($i));
            $this->assertSame($entity, $this->loader->getByOffset($i));
        }
        
        $this->assertSame(false, $this->loader->offsetExists(5));
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testNotFoundException()
    {
        foreach(range(0, 4) as $i)
            $this->loader->getByOffset($i);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getByOffset(5);
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testOffsetException()
    {
        $this->loader->getByOffset(0);
        $this->loader->getByOffset(1);
        $this->loader->getByOffset(1);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getByOffset(3);
    }

    /**
     * @covers ::getByOffset
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testEmptyIterator()
    {
        $this->data = array();
        $loader = $this->getLoader();
        $this->assertEquals(false, $loader->offsetExists(0));
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $loader->getByOffset(0);
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testCurrentEntity()
    {
        $this->loader->getByOffset(0);
        $this->assertEquals($this->loader->getByOffset(1), $this->loader->getByOffset(1));
        $this->loader->offsetExists(2);
        $this->loader->offsetExists(3);
        $entity4 = $this->loader->getByOffset(4);
        $this->assertEquals(false, $this->loader->offsetExists(5));
        $this->assertEquals(true, $this->loader->offsetExists(4));
        $this->assertEquals($entity4, $this->loader->getByOffset(4));
        
    }
    
    protected function getLoader()
    {
        $this->iterator = new \ArrayIterator($this->data);
        return new \PHPixie\ORM\Loaders\Loader\Repository\DataIterator($this->loaders, $this->repository, $this->iterator);
    }
    
}