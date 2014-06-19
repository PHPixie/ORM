<?php

namespace PHPixieTests\ORM\Model\Data\Data\Document\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Data\Data\Document\Type\Document
 */
class DocumentTest extends \PHPixieTests\ORM\Model\Data\Data\Document\TypeTest
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
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::set
     * @covers ::__set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->type->set('name', 'Blum');
        $this->assertEquals('Blum', $this->type->name);
        
        $spell = new \stdClass;
        $spellDocument = $this->document();
        $this->method($this->documentBuilder, 'document', $spellDocument, array($spell), 0);
        $this->type->set('spell', $spell);
        $this->assertEquals($spellDocument, $this->type->spell);
        
        $flowers = array();
        $flowersArray = $this->documentArray();
        $this->method($this->documentBuilder, 'documentArray', $flowersArray, array($flowers), 0);
        $this->type->flowers = $flowers;
        $this->assertEquals($flowersArray, $this->type->flowers);
    }
    
    /**
     * @covers ::addArray
     * @covers ::<protected>
     */
    public function testAddArray()
    {
        $flowers = array();
        $flowersArray = $this->documentArray();
        $this->method($this->documentBuilder, 'documentArray', $flowersArray, array($flowers), 0);
        $this->type->addArray('flowers', $flowers);
        $this->assertEquals($flowersArray, $this->type->flowers);
        
        $this->method($this->documentBuilder, 'documentArray', $flowersArray, array(array()), 0);
        $this->type->addArray('flowers');
        $this->assertEquals($flowersArray, $this->type->flowers);
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
        $this->type->addDocument('spell', $spell);
        $this->assertEquals($spellDocument, $this->type->spell);
        
        $this->method($this->documentBuilder, 'document', $spellDocument, array(null), 0);
        $this->type->addDocument('spell');
        $this->assertEquals($spellDocument, $this->type->spell);
    }
    
    /**
     * @covers ::currentData
     * @covers ::<protected>
     */
    public function testCurrentData()
    {
        $this->type->set('name', 'Blum');
        $this->type->color = 'green';
        unset($this->type->type);
        
        $this->method($this->magicDocument, 'currentData', 'test1', array(), 0);
        $this->method($this->treesArray, 'currentData', 'test2', array(), 0);
        
        $expect = (object) array(
            'name' => 'Blum',
            'magic' => 'test1',
            'trees' => 'test2',
            'color' => 'green'
        );
        
        $this->assertEquals($expect, $this->type->currentData());
        $this->setExpectedException('\PHPixie\ORM\Exception\Model');
        $this->type->t = new \ArrayIterator;
        $this->type->currentData();
        
    }
    
    protected function getType()
    {
        $this->magicDocument = $this->document();
        $this->method($this->documentBuilder, 'document', $this->magicDocument, array($this->data->magic), 0);
        
        $this->treesArray = $this->documentArray();
        $this->method($this->documentBuilder, 'documentArray', $this->treesArray, array($this->data->trees), 1);
        
        return new \PHPixie\ORM\Model\Data\Data\Document\Type\Document($this->documentBuilder, $this->data);
    }
}
