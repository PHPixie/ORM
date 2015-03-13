<?php

namespace PHPixie\Tests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\MultiplePreloader
 */
class MultiplePreloaderTest extends \PHPixie\Tests\ORM\Loaders\LoaderTest
{
    protected $multiplePreloader;
    protected $ids = array(11, 12, 13);
    
    public function setUp()
    {
        $this->multiplePreloader = $this->getMultiplePreloader();
        parent::setUp();
    }
    
    /**
     * @covers ::offsetExists
     */
    public function testOffsetExists()
    {
        foreach(range(0, 2) as $i)
            $this->assertEquals(true, $this->loader->OffsetExists($i));
        
        $this->assertEquals(false, $this->loader->OffsetExists(3));
    }
    
    /**
     * @covers ::getByOffset
     */
    public function testGetByOffset()
    {
        $entity = $this->getEntity();
        $this->method($this->multiplePreloader, 'getEntity', $entity, array(12), 0);
        $this->assertEquals($entity, $this->loader->getByOffset(1));
    }
    
    /**
     * @covers ::getByOffset
     */
    public function testNotFoundException(){
        $this->multiplePreloader
            ->expects($this->any())
            ->method('getEntity')
            ->will($this->returnCallback(function(){
                throw new \PHPixie\ORM\Exception\Loader();
            }));
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getbyOffset(2);
    }
    
    /**
     * @covers ::getByOffset
     */
    public function testOutOfBoundsException(){
        $except = false;
        
        try{
            $this->loader->getByOffset(4);
        }catch(\Exception $e){
            $except = true;
        }
        
        $this->assertSame(true, $except);
    }
    
    protected function getMultiplePreloader()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple');
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\MultiplePreloader($this->loaders, $this->multiplePreloader, $this->ids);
    }
}