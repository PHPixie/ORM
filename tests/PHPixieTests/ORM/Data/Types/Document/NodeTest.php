<?php

namespace PHPixieTests\ORM\Data\Type\Document;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Type\Document\Node
 */
abstract class NodeTest extends \PHPixieTests\AbstractORMTest
{
    protected $node;
    protected $documentBuilder;
    
    public function setUp()
    {
        $this->documentBuilder = $this->quickMock('\PHPixie\ORM\Data\Type\Document\Builder');
        $this->node = $this->getNode();
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Data\Type\Document\Node::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    protected function document()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Type\Document\Node\Document');
    }
    
    protected function arrayNode()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Type\Document\Node\ArrayNode');
    }
    
    abstract protected function getNode();
}