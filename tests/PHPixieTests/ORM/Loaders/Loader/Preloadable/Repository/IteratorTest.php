<?php

namespace PHPixieTests\ORM\Loaders\Loader\Preloadable\Repository;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Preloadable\Repository\Iterator
 */
class IteratorTest extends \PHPixieTests\ORM\Loaders\Loader\Preloadable\RepositoryTest
{
    protected $iterator;
    
    public function setUp()
    {
        parent::setUp();
    }
    
    /**
     * @covers ::iterator
     */
    public function testIterator()
    {
        $this->assertEquals($this->iterator, $this->loader->iterator());
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
    
    protected function getLoader()
    {
        $this->iterator = new \ArrayIterator($this->data);
        return new \PHPixie\ORM\Loaders\Loader\Preloadable\Repository\Iterator($this->loaders, $this->repository, $this->iterator);
    }
    
    protected function preparePreloadableModels(){
        foreach($this->preloadableModels as $key => $model) {
            $this->method($this->repository, 'load', $model, array($this->data[$key]), $key);
        }
    }
    
    
}