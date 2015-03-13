<?php

namespace PHPixie\Tests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Document
 */
class DocumentTest extends \PHPixie\Tests\ORM\Planners\PlannerTest
{
    protected $data;
    protected $document;
    
    public function setUp()
    {
        $this->data = (object) array(
            'fairy' => array(
                'name' => 'Trixie',
                'tree' => (object) array(
                    'name' => 'Oak'
                ),
                'spells' => array(
                    (object) array(
                        'name' => 'Rain'
                    )
                )
            )
        );
        
        $builder = new \PHPixie\ORM\Data\Types\Document\Builder();
        $this->document = new \PHPixie\ORM\Data\Types\Document\Node\Document($builder, $this->data);
        
        parent::setUp();
    }
    
    /**
     * @covers ::getDocument
     * @covers ::<protected>
     */
    public function testGetDocument()
    {
        $document = $this->planner->getDocument($this->document, 'fairy.tree');
        $this->assertSame('Oak', $document->name);
        $this->assertSame($document, $this->document->fairy->tree);
        
        $this->assertSame(null, $this->planner->getDocument($this->document, 'fairy.tree.pixie'));
        
        $document = $this->planner->getDocument($this->document, 'fairy.tree.pixie', true);
        $this->assertInstanceOf('\PHPixie\ORM\Data\Types\Document\Node\Document', $document);
        $this->assertSame($document, $this->document->fairy->tree->pixie);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Data');
        $this->planner->getDocument($this->document, 'fairy.spells');
    }
    
    /**
     * @covers ::getArrayNode
     * @covers ::<protected>
     */
    public function testGetArrayNode()
    {
        $array = $this->planner->getArrayNode($this->document, 'fairy.spells');
        $this->assertSame('Rain', $array[0]->name);
        $this->assertSame($array, $this->document->fairy->spells);
        
        $this->assertSame(null, $this->planner->getArrayNode($this->document, 'fairy.tree.pixie'));
        
        $array = $this->planner->getArrayNode($this->document, 'fairy.tree.pixie', true);
        $this->assertInstanceOf('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode', $array);
        $this->assertSame($array, $this->document->fairy->tree->pixie);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Data');
        $this->planner->getArrayNode($this->document, 'fairy.tree');
    }
    
    /**
     * @covers ::getParentDocumentandKey
     * @covers ::<protected>
     */
    public function testGetParentDocumentandKey()
    {
        list($document, $key) = $this->planner->getParentDocumentandKey($this->document, 'fairy.tree');
        $this->assertSame('Trixie', $document->name);
        $this->assertSame($this->document->fairy, $document);
        $this->assertSame('tree', $key);
        
        list($document, $key) = $this->planner->getParentDocumentandKey($this->document, 'fairy.tree.pixie.flower');
        $this->assertSame(null, $document);
        $this->assertSame('flower', $key);

        list($document, $key) = $this->planner->getParentDocumentandKey($this->document, 'fairy.tree.pixie.flower', true);
        $this->assertInstanceOf('\PHPixie\ORM\Data\Types\Document\Node\Document', $document);
        $this->assertSame($document, $this->document->fairy->tree->pixie);
        $this->assertSame('flower', $key);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Data');
        $this->planner->getParentDocumentandKey($this->document, 'fairy.spells.name');
    }
    
    protected function planner()
    {
        return new \PHPixie\ORM\Planners\Planner\Document();
    }
}