<?php

namespace PHPixieTests\ORM\Model\Data\Data\Document\Type\DocumentArray;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Data\Data\Document\Type\DocumentArray
 */
class IteratorTest extends \PHPixieTests\AbstractORMTest
{
    protected $iterator;
    protected $documentArray;
    protected $data = array('pixie', 'trixie', 'test');
    
    public function setUp()
    {
        $this->documentArray = $this->quickMock('\PHPixie\ORM\Model\Data\Data\Document\Type\DocumentArray');
        $this->iterator = new \PHPixie\ORM\Model\Data\Data\Document\Type\DocumentArray\Iterator($this->documentArray);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::next
     * @covers ::key
     * @covers ::current
     * @covers ::rewind
     * @covers ::<protected>
     */
    public function testIterator()
    {
        $this->prepareIterator();
        foreach($this->iterator as $key => $value) {
            $this->assertEquals($this->data[$key], $value);
        }
        
        $this->iterator->rewind();
        $this->assertEquals(0, $this->iterator->key());
    }
    
    protected function prepareIterator()
    {
        $data = $this->data;
        
        $this->method($this->documentArray, 'count', 3);
        
        $this->documentArray
                ->expects($this->any())
                ->method('offsetGet')
                ->will($this->returnCallback(function($offset) use ($data){
                    return $data[$offset];
                }));
    }
}