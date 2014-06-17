<?php

namespace PHPixieTests\ORM\Model\Data\Data\Document\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Data\Data\Document\Type\Document
 */
class DocumentTest extends \PHPixieTests\ORM\Model\Data\Data\Document\TypeTest
{
    protected $data;
    
    public function setUp()
    {
        $this->data = new \stdClass;
        
        $this->data->name = 'Trixie';
        $this->data->type = 'pixie';
        
        $this->data->magic = new \stdClass;
        $this->data->magic->type = 'air';
        $this->data->magic->level = 5;
        
        $this->data->trees = array('Oak', 'Pine');
        $this->data->friends = array(new \stdClass, new \stdClass);
        $this->data->friends[0]->name = 'Tinkerbell';
        $this->data->friends[1]->name = 'Blum';
        
        parent::setUp();
    }
    
    
    public function testAdd()
    {
    
    }
    
    
    protected function getType()
    {
        return new \PHPixie\ORM\Model\Data\Data\Document\Type\Document($this->documentBuilder, $this->data);
    }
}
