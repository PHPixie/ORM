<?php

namespace PHPixie\Tests\ORM\Data\Types\Document;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Types\Document\Node
 */
abstract class NodeTest extends \PHPixie\Test\Testcase
{
    protected $node;
    protected $documentBuilder;
    
    public function setUp()
    {
        $this->documentBuilder = $this->quickMock('\PHPixie\ORM\Data\Types\Document\Builder');
        $this->node = $this->getNode();
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Data\Types\Document\Node::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    protected function document()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
    }
    
    protected function arrayNode()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode');
    }
    
    abstract protected function getNode();
}