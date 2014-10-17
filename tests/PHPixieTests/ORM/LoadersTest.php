<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders
 */
class LoadersTest extends \PHPixieTests\AbstractORMTest
{
    protected $loaders;
    
    public function setUp()
    {
        $this->loaders = new \PHPixie\ORM\Loaders;
    }
    
    /**
     * @covers ::iterator
     */
    public function testIterator()
    {
        $loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader');
        $iterator = $this->loaders->iterator($loader);
        $this->assertInstanceOf('\PHPixie\ORM\Loaders\Iterator', $iterator);
        $this->assertAttributeEquals($loader, 'loader', $iterator);
    }
    
    /**
     * @covers ::arrayAccess
     */
    public function testArrayAccess()
    {
        $array = new \ArrayObject();
        $arrayAccess = $this->loaders->arrayAccess($array);
        $this->assertInstanceOf('\PHPixie\ORM\Loaders\Loader\ArrayAccess', $arrayAccess);
        $this->assertAttributeEquals($this->loaders, 'loaders', $arrayAccess);
        $this->assertAttributeEquals($array, 'arrayAccess', $arrayAccess);
    }
    
    /**
     * @covers ::resultPreloader
     */
    public function testReusltPreloader()
    {
        $preloader = $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Preloader');
        $ids = array(1, 2, 3);
        $resultPreloader = $this->loaders->resultPreloader($preloader, $ids);
        $this->assertInstanceOf('\PHPixie\ORM\Loaders\Loader\ResultPreloader', $resultPreloader);
        $this->assertAttributeEquals($this->loaders, 'loaders', $resultPreloader);
        $this->assertAttributeEquals($preloader, 'resultPreloader', $resultPreloader);
        $this->assertAttributeEquals($ids, 'ids', $resultPreloader);
    }
    
    /**
     * @covers ::editableProxy
     * @covers ::cachingProxy
     * @covers ::preloadingProxy
     */
    public function testProxies()
    {
        $loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader');
        foreach(array('editable', 'caching', 'preloading') as $type) {
            $method = $type.'Proxy';
            $proxy = $this->loaders->$method($loader);
            $this->assertInstanceOf('\PHPixie\ORM\Loaders\Loader\Proxy\\'.ucfirst($type), $proxy);
            $this->assertAttributeEquals($this->loaders, 'loaders', $proxy);
            $this->assertAttributeEquals($loader, 'loader', $proxy);
        }
    }
    
    /**
     * @covers ::reusableResult
     */
    public function testReusableResult()
    {
        $repository = $this->quickMock('\PHPixie\ORM\Loaders\Loader');
        $reusableResultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
            
        $reusableResult = $this->loaders->reusableResult($repository, $reusableResultStep);
        $this->assertAttributeEquals($this->loaders, 'loaders', $reusableResult);
        $this->assertAttributeEquals($repository, 'repository', $reusableResult);
        $this->assertAttributeEquals($reusableResultStep, 'reusableResultStep', $reusableResult);
    }
     
    /**
     * @covers ::dataIterator
     */
    public function testDataIterator()
    {
        $repository = $this->quickMock('\PHPixie\ORM\Loaders\Loader');
        $iterator = new \ArrayIterator();
            
        $dataIterator = $this->loaders->dataIterator($repository, $iterator);
        $this->assertAttributeEquals($this->loaders, 'loaders', $dataIterator);
        $this->assertAttributeEquals($repository, 'repository', $dataIterator);
        $this->assertAttributeEquals($iterator, 'dataIterator', $dataIterator);
    }
}