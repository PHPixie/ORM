<?php

namespace PHPixieTests\ORM\Model\Data\Data\Document\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Data\Data\Document\Type\DocumentArray
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
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    public function testOffset()
    {
        
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