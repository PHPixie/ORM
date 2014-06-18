<?php

namespace PHPixieTests\ORM\Model\Data\Data\Document\Type;

/**
 * @coversDefaultClass @covers \PHPixie\ORM\Model\Data\Data\Document\Type::__construct\DocumentArray
 */
class DocumentArrayTest extends \PHPixieTests\ORM\Model\Data\Data\Document\TypeTest
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
     * @covers ::__construct
     * @covers \PHPixie\ORM\Model\Data\Data\Document\Type::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
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
        $this->assertEquals(true, isset($this->type[2]));
        $this->assertEquals(false, isset($this->type[3]));
        $this->assertEquals('test', $this->type[2]);
        $this->type[3] = 5;
        $this->assertEquals(5, $this->type[3]);
        $this->assertEquals($this->items[0], $this->type[0]);
        $this->assertEquals($this->items[1], $this->type[1]);
        $this->type[0] = 5;
        $this->assertEquals(5, $this->type[0]);
        $this->type[]='test2';
        $this->assertEquals('test2', $this->type[4]);
        
        $documentArray = $this->documentArray();
        $this->method($this->documentBuilder, 'documentArray', $documentArray, array(array()), 0);
        $this->type[]=array();
        $this->assertEquals($documentArray, $this->type[5]);
        
    }
    
    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testIterator()
    {
        $iterator = $this->quickMock('\PHPixie\ORM\Model\Data\Data\Document\Type\DocumentArray\Iterator');
        $this->method($this->documentBuilder, 'arrayIterator', $iterator, array($this->type), 0);
        $this->assertEquals($iterator, $this->type->getIterator());
    }
    
    /**
     * @covers ::count
     * @covers ::<protected>
     */
    public function testCount()
    {
        $this->assertEquals(3, $this->type->count());
    }
    
    /**
     * @covers ::clear
     * @covers ::<protected>
     */
    public function testClear()
    {
        $this->type->clear();
        $this->assertEquals(0, $this->type->count());
    }
    
    /**
     * @covers ::append
     * @covers ::<protected>
     */
    public function testAppend()
    {
        $this->assertEquals($this->type, $this->type->append(5));
        $this->assertEquals(5, $this->type[3]);
    }
    
    /**
     * @covers ::last
     * @covers ::<protected>
     */
    public function testLast()
    {
        $this->assertEquals('test', $this->type->last());
    }
    
    /**
     * @covers ::appendArray
     * @covers ::<protected>
     */
    public function testAppendArray()
    {
        $documentArray = $this->documentArray();
        $this->method($this->documentBuilder, 'documentArray', $documentArray, array(array(5)), 0);
        $this->assertEquals($documentArray, $this->type->appendArray(array(5), 1));
        $this->assertEquals($documentArray, $this->type[1]);
        
        $this->method($this->documentBuilder, 'documentArray', $documentArray, array(array()), 0);
        $this->assertEquals($documentArray, $this->type->appendArray());
        $this->assertEquals($documentArray, $this->type[3]);
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
        $this->assertEquals($document, $this->type->appendDocument($object, 1));
        $this->assertEquals($document, $this->type[1]);
        
        $this->method($this->documentBuilder, 'document', $document, array(null), 0);
        $this->assertEquals($document, $this->type->appendDocument());
        $this->assertEquals($document, $this->type[3]);
    }
    
    protected function getType()
    {
        $this->items[0] = $this->document();
        $this->method($this->documentBuilder, 'document', $this->items[0], array($this->data[0]), 0);
        
        $this->items[1] = $this->documentArray();
        $this->method($this->documentBuilder, 'documentArray', $this->items[1], array($this->data[1]), 1);
        
        return new \PHPixie\ORM\Model\Data\Data\Document\Type\DocumentArray($this->documentBuilder, $this->data);
    }
}