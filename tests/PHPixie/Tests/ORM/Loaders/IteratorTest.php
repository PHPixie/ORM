<?php

namespace PHPixie\Tests\ORM\Loaders;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Iterator
 */
class IteratorTest extends \PHPixie\Test\Testcase
{
    protected $loader;
    protected $iterator;
    
    public function setUp()
    {
        $this->loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader');
        $this->iterator = new \PHPixie\ORM\Loaders\Iterator($this->loader);
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::valid
     * @covers ::next
     * @covers ::key
     * @covers ::current
     * @covers ::rewind
     */
    public function testIterator()
    {
        $this->prepareIterator();
        
        $this->iterator->next();
        $this->assertEquals(1, $this->iterator->current());
        $this->iterator->next();
        $this->iterator->next();
        $this->iterator->next();
        $this->assertEquals(false, $this->iterator->valid());
        $this->iterator->rewind();
        
        for($i=0; $i<5; $i++) {
            $valid = $i<3;
            
            $this->assertEquals($valid, $this->iterator->valid());
            $current = $valid?$i:2;
            $this->assertEquals($current, $this->iterator->current());
            $this->assertEquals($current, $this->iterator->current());
            $this->assertEquals($valid?$i:2, $this->iterator->key());
            $this->iterator->next();
        }
    }
    
    /**
     * @covers ::valid
     * @covers ::next
     * @covers ::key
     * @covers ::current
     * @covers ::rewind
     */
    public function testLoop()
    {
        for($i=0; $i<2; $i++){
            foreach($this->iterator as $key => $value){
                $this->assertEquals($key, $value);
            }
        }
    }
    
    protected function prepareIterator()
    {
        $this->loader
                ->expects($this->any())
                ->method('getByOffset')
                ->will($this->returnCallback(function($offset){
                    if($offset>2)
                        return null;
                    return $offset;
                }));
        
        $this->loader
                ->expects($this->any())
                ->method('offsetExists')
                ->will($this->returnCallback(function($offset){
                    if($offset>2)
                        return false;
                    return true;
                }));
    }
}
