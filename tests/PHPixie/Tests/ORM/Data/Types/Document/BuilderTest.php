<?php

namespace PHPixie\Tests\ORM\Data\Types\Document;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Types\Document\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $builder;

    public function setUp()
    {
        $this->builder = new \PHPixie\ORM\Data\Types\Document\Builder;
    }
    
    /**
     * @covers ::document
     */
    public function testDocument()
    {
        $document = $this->builder->document();
        $this->assertInstanceOf('\PHPixie\ORM\Data\Types\Document\Node\Document', $document);
        
        $pixie = new \stdClass;
        $pixie->name = 'Trixie';
        $document = $this->builder->document($pixie);
        $this->assertInstanceOf('\PHPixie\ORM\Data\Types\Document\Node\Document', $document);
        $this->assertEquals('Trixie', $document->name);
    }

    /**
     * @covers ::arrayIterator
     */
    public function testArrayIterator()
    {
        $array = $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode');
        $iterator = $this->builder->arrayIterator($array);
        $this->assertInstanceOf('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode\Iterator', $iterator);
        $this->method($array, 'offsetGet', 5, array(0), 0);
        $this->assertEquals(5, $iterator->current());
    }

    /**
     * @covers ::arrayNode
     */
    public function testArrayNode()
    {
        $array = $this->builder->arrayNode(array('Trixie'));
        $this->assertInstanceOf('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode', $array);
        $this->assertEquals('Trixie', $array[0]);

        $array = $this->builder->arrayNode();
        $this->assertInstanceOf('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode', $array);
    }

}