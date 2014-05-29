<?php

namespace PHPixieTests\ORM\Loaders\Loader\Repository;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository\DataIterator
 */
class DataIteratorTest extends \PHPixieTests\ORM\Loaders\Loader\RepositoryTest
{
    
    protected $iterator;
    
    public function setUp()
    {
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
    public function testCurrentModel()
    {
        $this->loader->getByOffset(0);
        $this->assertEquals($this->loader->getByOffset(1), $this->loader->getByOffset(1));
        $this->loader->offsetExists(2);
        $this->loader->offsetExists(3);
        $model4 = $this->loader->getByOffset(4);
        $this->assertEquals(false, $this->loader->offsetExists(5));
        $this->assertEquals(true, $this->loader->offsetExists(4));
        $this->assertEquals($model4, $this->loader->getByOffset(4));
        
    }
    
    protected function getLoader()
    {
        $this->iterator = new \ArrayIterator($this->data);
        return new \PHPixie\ORM\Loaders\Loader\Repository\DataIterator($this->loaders, $this->repository, $this->iterator);
    }
    
}