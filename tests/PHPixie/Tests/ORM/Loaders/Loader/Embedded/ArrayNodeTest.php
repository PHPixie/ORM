<?php

namespace PHPixie\Tests\ORM\Loaders\Loader\Embedded;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Embedded\ArrayNode
 */
class ArrayNodeTest extends \PHPixie\Tests\ORM\Loaders\Loader\EmbeddedTest
{
    protected $arrayNode;
    protected $owner;
    protected $ownerPropertyName = 'fairies';
    
    protected $documents;
    
    public function setUp()
    {
        $this->arrayNode = $this->getArrayNode();
        $this->owner = $this->getEntity();
        
        $documents = array();
        for($i=0; $i<3; $i++) {
            $documents[] = $this->getDocument();    
        }
        
        $this->documents = $documents;
        
        $this->method($this->arrayNode, 'count', count($this->documents), array());
        
        $this->method($this->arrayNode, 'offsetGet', function($i) use($documents){
            return $documents[$i];
        });
        
        $this->method($this->arrayNode, 'offsetExists', function($i) use($documents){
            return $i < count($documents);
        });
        
        
        parent::setUp();
    }
    
    /*
     * @covers ::__construct
     * @covers ::\PHPixie\ORM\Loaders\Loader\Embedded::__construct
     * @covers \PHPixie\ORM\Loaders\Loader::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }

    /**
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testOffsetExists()
    {
        $this->assertEquals(true, $this->loader->offsetExists(2));
        $this->assertEquals(false, $this->loader->offsetExists(3));
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testOffsetGet()
    {
        $entities = $this->prepareEntities();
        foreach($entities as $key => $entity) {
            $this->assertSame($entity, $this->loader->getByOffset($key));
            $this->assertSame($entity, $this->loader->getByOffset($key));
        }
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
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testNotFoundException()
    {
        $this->setExpectedException('\Exception');
        $this->loader->getByOffset(100);
    }
    
    /**
     * @covers ::count
     * @covers ::<protected>
     */
    public function testCount()
    {
        $this->assertSame(3, $this->loader->count());
    }
    
    /**
     * @covers ::cacheEntity
     * @covers ::shiftCachedEntities
     * @covers ::getCachedEntity
     * @covers ::getCachedEntities
     * @covers ::clearCachedEntities
     * @covers ::<protected>
     */
    public function testCachedEntities()
    {
        $this->assertSame(null, $this->loader->getCachedEntity(0));
        $this->assertSame(array(), $this->loader->getCachedEntities());
        
        $entities = $this->prepareEntities(array(0, 2));
        
        $this->loader->getByOffset(0);
        $this->loader->getByOffset(2);
        
        $this->assertSame($entities[0], $this->loader->getCachedEntity(0));
        $this->assertSame($entities[2], $this->loader->getCachedEntity(2));
        
        $this->assertSame(array(
            0 => $entities[0],
            2 => $entities[2],
        ), $this->loader->getCachedEntities());
        
        $this->loader->shiftCachedEntities(1);
        
        $this->assertSame(null, $this->loader->getCachedEntity(2));
        $this->assertSame($entities[2], $this->loader->getCachedEntity(1));
        
        $this->loader->shiftCachedEntities(1);
        $this->assertSame(null, $this->loader->getCachedEntity(1));
        
        $this->assertSame($entities[0], $this->loader->getCachedEntity(0));
        
        $this->loader->clearCachedEntities();
        $this->assertSame(null, $this->loader->getCachedEntity(0));
        
        $entity = $this->getEntity();
        $this->loader->cacheEntity(0, $entity);
        
        $this->assertSame($entity, $this->loader->getCachedEntity(0));
        $this->assertSame($entity, $this->loader->getByOffset(0));
    }
    
    protected function prepareEntities($keys = null)
    {
        if($keys === null) {
            $keys = array_keys($this->documents);
        }
        
        $entities = array();
        foreach($keys as $at => $key) {
            $entity = $this->prepareLoadEntity($this->documents[$key], $at);
            $this->method($entity, 'setOwnerRelationship', null, array($this->owner, $this->ownerPropertyName), 0);
            $entities[$key]=$entity;
        }
        
        return $entities;
    }

    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Embedded\Entity');
    }
    
    protected function getArrayNode()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode');
    }
    
    protected function getDocument()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Embedded\ArrayNode(
            $this->loaders,
            $this->embeddedModel,
            $this->modelName,
            $this->arrayNode,
            $this->owner,
            $this->ownerPropertyName
        );
    }
}