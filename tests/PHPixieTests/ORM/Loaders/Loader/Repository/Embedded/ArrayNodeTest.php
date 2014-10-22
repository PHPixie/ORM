<?php

namespace PHPixieTests\ORM\Loaders\Loader\Repository\Embedded;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository\Embedded\ArrayNode
 */
abstract class ArrayNodeTest extends \PHPixieTests\ORM\Loaders\Loader\Repository\EmbeddedTest
{

    protected $arrayNode;
    protected $owner;
    protected $ownerReslationship;
    protected $models = array();
    protected $documents = array();

    public function setUp()
    {
        $this->arrayNode = $this->getArrayNode();
        $this->owner = $this->getOwner();
        $this->ownerRelationship = 'flowers';
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        parent::__construct();
    }

    /**
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testOffsetExists()
    {
        $this->method($this->arrayNode, 'offsetExists', true, array(3), 0);
        $this->assertSame(true, $this->loader->offsetExists(3));

        $this->method($this->arrayNode, 'offsetExists', false, array(3), 0);
        $this->assertSame(true, $this->loader->offsetExists(3));
    }

    /**
     * @covers ::count
     * @covers ::<protected>
     */
    public function testCount()
    {
        $this->method($this->arrayNode, 'count', 5, array(), 0);
        $this->assertSame(5, $this->loader->count());
    }

    /**
     * @covers ::offsetGet
     * @covers ::<protected>
     */
    public function testOffsetGet()
    {
        $this->method($this->arrayNode, 'offsetExists', true, array(3), 0);
        $document = $this->getDocument();
        $model = $this->prepareLoad($document, 0);

        $this->assertSame($model, $this->loader->offsetGet(3));
        $this->assertSame($model, $this->loader->offsetGet(3));

        $this->method($this->arrayNode, 'offsetExists', false, array(4), 0);
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->offsetGet(4);
    }

    /**
     * @covers ::cacheModel
     * @covers ::<protected>
     */
    public function cacheModel()
    {
        $model = $this->getModel();
        $this->loader->cacheModel(3, $model);
        $this->assertSame($model, $this->loader->offsetGet(3));
    }

    protected function getArrayNode()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode');
    }

    protected function getDocument()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
    }

    protected function getOwner()
    {
        return $this->getModel();
    }

    protected function getModel()
    {
        return $this->quickMock('\PHPixie\ORM\Repositories\Type\Embedded\Model');
    }

    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Repository\Embedded\ArrayNode(
            $this->loaders,
            $this->repository,
            $this->arrayNode,
            $this->owner,
            $this->ownerRelationship
        );
    }
}
