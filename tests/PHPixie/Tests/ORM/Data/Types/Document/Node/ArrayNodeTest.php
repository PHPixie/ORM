<?php

namespace PHPixie\Tests\ORM\Data\Types\Document\Node;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Types\Document\Node\ArrayNode
 */
class ArrayNodeTest extends \PHPixie\Tests\ORM\Data\Types\Document\NodeTest
{
    protected $data;
    protected $items = array();
    
    public function setUp()
    {
        $this->data = array(
            new \stdClass,
            array('Oak', 'Pine'),
            'test'
        );
        
        $this->data[0]->name='Pixie';
    
        parent::setUp();
    }

    /**
     * @covers ::offsetGet
     * @covers ::offsetSet
     * @covers ::offsetUnset
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testOffset()
    {
        $this->assertEquals(true, isset($this->node[2]));
        $this->assertEquals(false, isset($this->node[3]));
        $this->assertEquals('test', $this->node[2]);
        $this->node[3] = 5;
        $this->assertEquals(5, $this->node[3]);
        $this->assertEquals($this->items[0], $this->node[0]);
        $this->assertEquals($this->items[1], $this->node[1]);
        $this->node[0] = 5;
        $this->assertEquals(5, $this->node[0]);
        $this->node[]='test2';
        $this->assertEquals('test2', $this->node[4]);
        
        $arrayNode = $this->arrayNode();
        $this->method($this->documentBuilder, 'arrayNode', $arrayNode, array(array()), 0);
        $this->node[]=array();
        $this->assertEquals($arrayNode, $this->node[5]);
        
        $document = $this->document();
        $this->method($this->documentBuilder, 'document', $document, array(array('t' => 1)), 0);
        $this->node[]=array('t' => 1);
        $this->assertEquals($document, $this->node[6]);
        unset($this->node[6]);
        
        unset($this->node[0]);
        $this->assertEquals($arrayNode, $this->node[4]);
        $this->assertEquals($this->items[1], $this->node[0]);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Data');
        $this->node[6] = 'test';
    }

    /**
     * @covers ::data
     * @covers ::<protected>
     */
    public function testData()
    {
        $this->method($this->items[0], 'data', $this->data[0], array(), 0);
        $this->method($this->items[1], 'data', $this->data[1], array(), 0);
        
        $data = $this->data;
        $this->assertEquals($data, $this->node->data());
        
        $this->method($this->items[0], 'data', $this->data[0], array(), 0);
        $this->method($this->items[1], 'data', $this->data[1], array(), 0);
        
        $data[]='test';
        $this->node[]='test';
        $this->assertEquals($data, $this->node->data());
    }
    
    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testIterator()
    {
        $iterator = $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode\Iterator');
        $this->method($this->documentBuilder, 'arrayIterator', $iterator, array($this->node), 0);
        $this->assertEquals($iterator, $this->node->getIterator());
    }
    
    /**
     * @covers ::count
     * @covers ::<protected>
     */
    public function testCount()
    {
        $this->assertEquals(3, $this->node->count());
    }
    
    /**
     * @covers ::clear
     * @covers ::<protected>
     */
    public function testClear()
    {
        $this->node->clear();
        $this->assertEquals(0, $this->node->count());
    }
    
    /**
     * @covers ::append
     * @covers ::<protected>
     */
    public function testAppend()
    {
        $this->assertEquals($this->node, $this->node->append(5));
        $this->assertEquals(5, $this->node[3]);
    }
    
    /**
     * @covers ::last
     * @covers ::<protected>
     */
    public function testLast()
    {
        $this->assertEquals('test', $this->node->last());
    }
    
    /**
     * @covers ::appendArray
     * @covers ::<protected>
     */
    public function testAppendArray()
    {
        $arrayNode = $this->arrayNode();
        $this->method($this->documentBuilder, 'arrayNode', $arrayNode, array(array(5)), 0);
        $this->assertEquals($arrayNode, $this->node->appendArray(array(5), 1));
        $this->assertEquals($arrayNode, $this->node[1]);
        
        $this->method($this->documentBuilder, 'arrayNode', $arrayNode, array(array()), 0);
        $this->assertEquals($arrayNode, $this->node->appendArray());
        $this->assertEquals($arrayNode, $this->node[3]);
    }
    
    /**
     * @covers ::appendDocument
     * @covers ::<protected>
     */
    public function testAppendDocument()
    {
        $document = $this->document();
        $object = new \stdClass;
        $this->method($this->documentBuilder, 'document', $document, array($object), 0);
        $this->assertEquals($document, $this->node->appendDocument($object, 1));
        $this->assertEquals($document, $this->node[1]);
        
        $this->method($this->documentBuilder, 'document', $document, array(null), 0);
        $this->assertEquals($document, $this->node->appendDocument());
        $this->assertEquals($document, $this->node[3]);
    }
    
    protected function getNode()
    {
        $this->items[0] = $this->document();
        $this->method($this->documentBuilder, 'document', $this->items[0], array($this->data[0]), 0);
        
        $this->items[1] = $this->arrayNode();
        $this->method($this->documentBuilder, 'arrayNode', $this->items[1], array($this->data[1]), 1);
        
        return new \PHPixie\ORM\Data\Types\Document\Node\ArrayNode($this->documentBuilder, $this->data);
    }
}