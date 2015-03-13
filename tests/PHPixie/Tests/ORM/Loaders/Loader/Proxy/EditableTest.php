<?php

namespace PHPixie\Tests\ORM\Loaders\Loader\Proxy;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Proxy\Editable
 */
class EditableTest extends \PHPixie\Tests\ORM\Loaders\Loader\ProxyTest
{
    protected $ids = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');
    
    /**
     * @covers ::<protected>
     * @covers ::add
     * @covers ::remove
     * @covers ::offsetExists
     * @covers ::getByOffset
     */
    public function testEdit()
    {
        $this->prepareSubloader();
        
        $this->assertItems(array(0, 2, 3, 4));
        
        $this->assertItems(array(0, 2, 3, 4));
        
        $entity5 = $this->entity(5);
        $this->loader->add(array($entity5));
        $this->assertItems(array(0, 2, 3, 4, 5));
        $this->assertEquals(false, $this->loader->offsetExists(5));
        $this->assertEquals(true, $this->loader->offsetExists(3));
        
        $entity7 = $this->entity(7);
        $entity7->setIsDeleted(true);
        $this->loader->add(array($entity7));
        $this->loader->add(array($entity7));
        $this->assertItems(array(0, 2, 3, 4, 5));
        
        
        $this->loader->add(array($this->entity(6)));
        $this->assertItems(array(0, 2, 3, 4, 5, 6));
        
        
        $entity5->setIsDeleted(true);
        $this->assertItems(array(0, 2, 3, 4, 6));
        
        
        $entity0 = $this->loader->getByOffset(0);
        $entity0->setIsDeleted(true);
        $this->assertItems(array(2, 3, 4, 6));
        
        
        $this->loader->remove(array($this->loader->getByOffset(2)));
        $this->assertItems(array(2, 3, 6));
        
        $this->loader->remove(array($this->loader->getByOffset(0), $this->loader->getByOffset(1)));
        $this->assertItems(array(6));
        $this->assertEquals(true, $this->loader->offsetExists(0));
        
        $this->loader->remove(array($this->loader->getByOffset(0)));
        $this->assertItems(array());
        $this->assertEquals(false, $this->loader->offsetExists(0));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::add
     * @covers ::remove
     * @covers ::offsetExists
     * @covers ::getByOffset
     */
    public function testRemoveAndAdd()
    {
        $this->prepareSubloader();
        $this->assertItems(array(0, 2, 3, 4));
        
        $entity3 = $this->loader->getByOffset(2);
        
        $this->loader->remove(array($entity3));
        $this->assertItems(array(0, 2, 4));
        
        $this->loader->add(array($entity3));
        $this->assertItems(array(0, 2, 4, 3));
        
        $entity5 = $this->entity(5);
        $this->loader->add(array($entity5));
        $this->loader->remove(array($entity5));
        $this->loader->add(array($entity5));
        $this->assertItems(array(0, 2, 4, 3, 5));
        
        $this->loader->remove(array($entity5));
        $this->assertItems(array(0, 2, 4, 3));
        
        $this->loader->add(array($entity5));
        $this->assertItems(array(0, 2, 4, 3, 5));
    }

    /**
     * @covers ::accessedEntities
     * @covers ::<protected>
     */
    public function testAccessedEntities()
    {
        $this->prepareSubloader();
        $this->loader->getByOffset(0);
        $this->loader->getByOffset(1);
        
        $accessedIds = array();
        foreach($this->loader->accessedEntities() as $entity) {
            $accessedIds[]=$entity->id();
        }
        
        $this->assertSame(array('a', 'c'), $accessedIds);
        
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testRemovedNotFoundException()
    {
        $this->prepareSubloader();
        $this->loader->removeAll();
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getByOffset(0);
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testMaxAllowedException()
    {
        $this->prepareSubloader();
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getByOffset(1);
    }
    
    
    /**
     * @covers ::<protected>
     * @covers ::add
     * @covers ::remove
     * @covers ::removeAll
     * @covers ::offsetExists
     * @covers ::getByOffset
     */
    public function testRemoval()
    {
        $this->prepareSubloader();
        $this->assertItems(array(0, 2, 3, 4));
        $this->loader->add(array($this->entity(5)));
        $this->assertItems(array(0, 2, 3, 4, 5));
        $this->loader->removeAll();
        $this->assertItems(array());
    }

    /**
     * @covers ::<protected>
     * @covers ::add
     * @covers ::remove
     * @covers ::offsetExists
     * @covers ::getByOffset
     */
    public function testRemoveBeforeLoad()
    {
        $this->prepareSubloader();
        $this->loader->remove(array($this->entity(2)));
        $this->assertItems(array(0, 3, 4));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::add
     * @covers ::remove
     * @covers ::offsetExists
     * @covers ::getByOffset
     */
    public function testRemoveAllByOneBeforeLoad()
    {
        $this->prepareSubloader();
        $this->loader->remove(array(
            $this->entity(0),
            $this->entity(2),
            $this->entity(3),
            $this->entity(4),
        ));
        $this->loader->add(array(
            $this->entity(5),
        ));
        $this->assertItems(array(5));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::removeAll
     * @covers ::offsetExists
     * @covers ::getByOffset
     * @covers ::add
     */
    public function testRemoveAllBeforeLoad()
    {
        $this->prepareSubloader();
        $this->loader->removeAll();
        $this->assertItems(array());
        $this->loader->add(array(
            $this->entity(5),
        ));
        $this->assertItems(array(5));
    }
    
    protected function assertItems($expected)
    {
        $ids = array();
        $i=0;
        while($this->loader->offsetExists($i)){
            $ids[]=$this->loader->getByOffset($i)->id();
            $i++;
        }
        
        $expectedIds = array();
        foreach($expected as $idKey)
            $expectedIds[]=$this->ids[$idKey];
        $this->assertEquals($expectedIds, $ids);
    }
    
    protected function entity($id, $isDeleted = false)
    {
        $id=$this->ids[$id];
        $entity = $this->getEntity();
        
        $this->method($entity, 'id', $id, array());
        
        $this->method($entity, 'isDeleted', function() use(&$isDeleted) {
            return $isDeleted;
        }, array());
        
        $this->method($entity, 'setIsDeleted', function($newIsDeleted) use(&$isDeleted) {
            $isDeleted = $newIsDeleted;
        });
        
        return $entity;
    }    
    
    protected function prepareSubloader()
    {
        $entities = array();
        for($i=0; $i<5; $i++)
            $entities[] = $this->entity($i, $i==1);
        
        $this->subloader
                    ->expects($this->any())
                    ->method('offsetExists')
                    ->will($this->returnCallback(function($offset) use($entities) {
                        return array_key_exists($offset, $entities);
                    }));
        
        $this->subloader
                    ->expects($this->any())
                    ->method('getByOffset')
                    ->will($this->returnCallback(function($offset) use($entities) {
                        if(!array_key_exists($offset, $entities))
                            throw new \Exception();
                        return $entities[$offset];
                    }));
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Proxy\Editable($this->loaders, $this->subloader);
    }
}