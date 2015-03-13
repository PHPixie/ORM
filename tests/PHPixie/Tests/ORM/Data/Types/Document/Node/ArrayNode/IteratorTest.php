<?php

namespace PHPixie\Tests\ORM\Data\Types\Document\Node\ArrayNode;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Types\Document\Node\ArrayNode\Iterator
 */
class IteratorTest extends \PHPixie\Test\Testcase
{
    protected $iterator;
    protected $arrayNode;
    protected $data = array('pixie', 'trixie', 'test');
    
    public function setUp()
    {
        $this->arrayNode = $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode');
        $this->iterator = new \PHPixie\ORM\Data\Types\Document\Node\ArrayNode\Iterator($this->arrayNode);
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
     * @covers ::valid
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
        
        $this->method($this->arrayNode, 'count', 3);
        
        $this->arrayNode
                ->expects($this->any())
                ->method('offsetGet')
                ->will($this->returnCallback(function($offset) use ($data){
                    return $data[$offset];
                }));
    }
}