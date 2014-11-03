<?php

namespace PHPixieTests\ORM\Loaders\Loader\Repository\Embedded;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository\Embedded\ArrayNode
 */
class ArrayNodeTest extends \PHPixieTests\ORM\Loaders\Loader\Repository\EmbeddedTest
{

    protected $arrayNode;
    protected $owner;
    protected $ownerPropertyName;
    protected $models = array();
    protected $documents = array();

    public function setUp()
    {
        $this->arrayNode = $this->getArrayNode();
        $this->owner = $this->getOwner();
        $this->ownerPropertyName = 'flowers';
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
        $this->assertSame(false, $this->loader->offsetExists(3));
    }

    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testGetByOffset()
    {
        $document = $this->getDocument();
        $this->method($this->arrayNode, 'offsetExists', true, array(3), 0);
        $this->method($this->arrayNode, 'offsetGet', $document, array(3), 1);
        $model = $this->prepareLoad($document, 0);

        $this->assertSame($model, $this->loader->getByOffset(3));
        $this->assertSame($model, $this->loader->getByOffset(3));
    }

    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testNotFoundException()
    {
        $this->method($this->arrayNode, 'offsetExists', false, array(4), 0);
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getByOffset(4);
    }

    /**
     * @covers ::cacheModel
     * @covers ::<protected>
     */
    public function testCacheModel()
    {
        $model = $this->getModel();
        $this->loader->cacheModel(3, $model);
        $this->assertSame($model, $this->loader->getByOffset(3));
    }

    /**
     * @covers ::shiftCachedModels
     * @covers ::<protected>
     */
    public function testShiftCachedModels()
    {
        $models = array();
        foreach(array(1,2,4,6,7) as $key) {
            $models[$key] = $this->getModel();
            $this->loader->cacheModel($key, $models[$key]);
        }

        $this->loader->shiftCachedModels(2);
        $this->assertSame(array(
            1 => $models[1],
            3 => $models[4],
            5 => $models[6],
            6 => $models[7]
        ), $this->loader->cachedModels());

        $this->loader->shiftCachedModels(4);
        $this->assertSame(array(
            1 => $models[1],
            3 => $models[4],
            4 => $models[6],
            5 => $models[7]
        ), $this->loader->cachedModels());
    }

    /**
     * @covers ::getCachedModel
     * @covers ::<protected>
     */
    public function testGetCachedModel()
    {
        $model = $this->getModel();
        $this->loader->cacheModel(3, $model);
        $this->assertSame($model, $this->loader->getCachedModel(3));
        $this->assertSame(null, $this->loader->getCachedModel(2));
    }

    /**
     * @covers ::clearCachedModels
     * @covers ::<protected>
     */
    public function testClearCachedModels()
    {
        $model = $this->getModel();
        $this->loader->cacheModel(3, $model);
        $this->assertSame(array(3 => $model), $this->loader->cachedModels());
        $this->loader->clearCachedModels();
        $this->assertSame(array(), $this->loader->cachedModels());
    }

    /**
     * @covers ::cachedModels
     * @covers ::<protected>
     */
    public function testCachedModels()
    {
        $this->assertSame(array(), $this->loader->cachedModels());
        $model = $this->getModel();
        $this->loader->cacheModel(3, $model);
        $this->assertSame(array(3 => $model), $this->loader->cachedModels());
    }

    /**
     * @covers ::arrayNode
     * @covers ::<protected>
     */
    public function testArrayNode()
    {
        $this->assertSame($this->arrayNode, $this->loader->arrayNode());
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
     * @covers ::owner
     * @covers ::<protected>
     */
    public function testOwner()
    {
        $this->assertSame($this->owner, $this->loader->owner());
    }

    /**
     * @covers ::ownerPropertyName
     * @covers ::<protected>
     */
    public function testOwnerPropertyName()
    {
        $this->assertSame($this->ownerPropertyName, $this->loader->ownerPropertyName());
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
            $this->ownerPropertyName
        );
    }
}
