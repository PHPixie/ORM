<?php

namespace PHPixieTests\ORM\Data\Types\Document\Node;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Types\Document\Node\Document
 */
class DocumentTest extends \PHPixieTests\ORM\Data\Types\Document\NodeTest
{
    protected $data;
    protected $magicDocument;
    protected $treesArray;
    
    public function setUp()
    {
        $this->data = new \stdClass;
        
        $this->data->name = 'Trixie';
        $this->data->type = 'pixie';
        
        $this->data->magic = new \stdClass;
        $this->data->magic->type = 'air';
        $this->data->magic->level = 5;
        
        $this->data->trees = array('Oak', 'Pine');
    
        parent::setUp();
    }

    /**
     * @covers ::set
     * @covers ::__set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->node->set('name', 'Blum');
        $this->assertEquals('Blum', $this->node->name);
        
        $spell = new \stdClass;
        $spellDocument = $this->document();
        $this->method($this->documentBuilder, 'document', $spellDocument, array($spell), 0);
        $this->node->set('spell', $spell);
        $this->assertEquals($spellDocument, $this->node->spell);
        
        $flowers = array();
        $flowersArray = $this->arrayNode();
        $this->method($this->documentBuilder, 'arrayNode', $flowersArray, array($flowers), 0);
        $this->node->flowers = $flowers;
        $this->assertEquals($flowersArray, $this->node->flowers);
    }
    
    /**
     * @covers ::addArray
     * @covers ::<protected>
     */
    public function testAddArray()
    {
        $flowers = array();
        $flowersArray = $this->arrayNode();
        $this->method($this->documentBuilder, 'arrayNode', $flowersArray, array($flowers), 0);
        $this->node->addArray('flowers', $flowers);
        $this->assertEquals($flowersArray, $this->node->flowers);
        
        $this->method($this->documentBuilder, 'arrayNode', $flowersArray, array(array()), 0);
        $this->node->addArray('flowers');
        $this->assertEquals($flowersArray, $this->node->flowers);
    }
    
    /**
     * @covers ::addDocument
     * @covers ::<protected>
     */
    public function testAddDocument()
    {
        $spell = new \stdClass;
        $spellDocument = $this->document();
        $this->method($this->documentBuilder, 'document', $spellDocument, array($spell), 0);
        $this->node->addDocument('spell', $spell);
        $this->assertEquals($spellDocument, $this->node->spell);
        
        $this->method($this->documentBuilder, 'document', $spellDocument, array(null), 0);
        $this->node->addDocument('spell');
        $this->assertEquals($spellDocument, $this->node->spell);
    }
    
    /**
     * @covers ::data
     * @covers ::<protected>
     */
    public function testCurrentData()
    {
        $this->node->set('name', 'Blum');
        $this->node->color = 'green';
        unset($this->node->type);
        
        $this->method($this->magicDocument, 'data', 'test1', array(), 0);
        $this->method($this->treesArray, 'data', 'test2', array(), 0);
        
        $expect = (object) array(
            'name' => 'Blum',
            'magic' => 'test1',
            'trees' => 'test2',
            'color' => 'green'
        );
        
        $this->assertEquals($expect, $this->node->data());
        $this->setExpectedException('\PHPixie\ORM\Exception\Model');
        $this->node->t = new \ArrayIterator;
        $this->node->data();
        
    }
    
    /**
     * @covers ::data
     * @covers ::get
     * @covers ::__set
     */
    public function testOriginalNull()
    {
        $document = new \PHPixie\ORM\Data\Types\Document\Node\Document($this->documentBuilder);
        $document->name = 'Blum';
        $this->assertEquals('Blum', $document->get('name'));
        $this->assertEquals((object) array('name' => 'Blum'), $document->data());
    }
    
    protected function getNode()
    {
        $this->magicDocument = $this->document();
        $this->method($this->documentBuilder, 'document', $this->magicDocument, array($this->data->magic), 0);
        
        $this->treesArray = $this->arrayNode();
        $this->method($this->documentBuilder, 'arrayNode', $this->treesArray, array($this->data->trees), 1);
        
        return new \PHPixie\ORM\Data\Types\Document\Node\Document($this->documentBuilder, $this->data);
    }
}
