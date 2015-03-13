<?php

namespace PHPixie\Tests\ORM\Data\Types;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Types\Document
 */
class DocumentTest extends \PHPixie\Tests\ORM\Data\Type\ImplementationTest
{
    protected $document;
    
    public function setUp()
    {
        $this->document = $this->document();
        parent::setUp();
    }
    
    /**
     * @covers ::data
     * @covers ::<protected>
     */
    public function testData()
    {
        $data = new \stdClass;
        $this->method($this->document, 'data', $data, array(), 0);
        $this->assertEquals($data, $this->type->data());
    }
    
    /**
     * @covers ::get
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSetGet()
    {
        $this->method($this->document, 'get', 5, array('flowers', null), 0);
        $this->assertEquals(5, $this->type->get('flowers'));
        
        $this->method($this->document, 'get', 5, array('flowers', 7), 0);
        $this->assertEquals(5, $this->type->get('flowers', 7));
        
        $this->method($this->document, 'set', null, array('flowers', 5), 0);
        $this->type->set('flowers', 5);
    }
    
    /**
     * @covers ::getRequired
     * @covers ::<protected>
     */
    public function testGetRequired()
    {
        $this->method($this->document, 'getRequired', 5, array('flowers'), 0);
        $this->assertEquals(5, $this->type->getRequired('flowers'));
    }
    
    /**
     * @covers ::addArray
     * @covers ::<protected>
     */
    public function testAddArray()
    {
        $array = $this->arrayNode();
        
        $this->method($this->document, 'addArray', $array, array('test', array(5)), 0);
        $this->assertEquals($array, $this->type->addArray('test', array(5)));
        
        $this->method($this->document, 'addArray', $array, array('test', array()), 0);
        $this->assertEquals($array, $this->type->addArray('test'));
    }
    
    /**
     * @covers ::addDocument
     * @covers ::<protected>
     */
    public function testAddDocument()
    {
        $document = $this->document();
        $data = new \stdClass;
        
        $this->method($this->document, 'addDocument', $document, array('test', $data), 0);
        $this->assertEquals($document, $this->type->addDocument('test', $data));
        
        $this->method($this->document, 'addDocument', $document, array('test', null), 0);
        $this->assertEquals($document, $this->type->addDocument('test'));
    }
    
    
    /**
     * @covers ::document
     * @covers ::<protected>
     */
    public function testDocument()
    {
        $this->assertSame($this->document, $this->type->document());
    }
    
    protected function arrayNode()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode');
    }
    
    protected function document()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
    }
    
    protected function getType()
    {
        return new \PHPixie\ORM\Data\Types\Document($this->document);
    }
    
}