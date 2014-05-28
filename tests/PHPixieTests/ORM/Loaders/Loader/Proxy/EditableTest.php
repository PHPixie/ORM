<?php

namespace PHPixieTests\ORM\Loaders\Loader\Proxy;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Proxy\Editable
 */
class EditableTest extends \PHPixieTests\ORM\Loaders\Loader\ProxyTest
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
        
        $model5 = $this->model(5);
        $this->loader->add(array($model5));
        $this->assertItems(array(0, 2, 3, 4, 5));
        $this->assertEquals(false, $this->loader->offsetExists(5));
        $this->assertEquals(true, $this->loader->offsetExists(3));
        
        $model7 = $this->model(7);
        $model7->setIsDeleted(true);
        $this->loader->add(array($model7));
        $this->assertItems(array(0, 2, 3, 4, 5));
        
        
        $this->loader->add(array($this->model(6)));
        $this->assertItems(array(0, 2, 3, 4, 5, 6));
        
        
        $model5->setIsDeleted(true);
        $this->assertItems(array(0, 2, 3, 4, 6));
        
        
        $model0 = $this->loader->getByOffset(0);
        $model0->setIsDeleted(true);
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
    public function testRemovedAndAdd()
    {
        $this->prepareSubloader();
        $this->assertItems(array(0, 2, 3, 4));
        
        $model3 = $this->loader->getByOffset(2);
        
        $this->loader->remove(array($model3));
        $this->assertItems(array(0, 2, 4));
        
        $this->loader->add(array($model3));
        $this->assertItems(array(0, 2, 4, 3));
        
        $model5 = $this->model(5);
        $this->loader->add(array($model5));
        $this->assertItems(array(0, 2, 4, 3, 5));
        
        $this->loader->remove(array($model5));
        $this->assertItems(array(0, 2, 4, 3));
        
        $this->loader->add(array($model5));
        $this->assertItems(array(0, 2, 4, 3, 5));
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
        $this->loader->add(array($this->model(5)));
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
        $this->loader->remove(array($this->model(2)));
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
            $this->model(0),
            $this->model(2),
            $this->model(3),
            $this->model(4),
        ));
        $this->loader->add(array(
            $this->model(5),
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
            $this->model(5),
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
    
    protected function model($id, $isDeleted = false)
    {
        $id=$this->ids[$id];
        $model = $this->quickMock('\PHPixie\ORM\Model\Repository\Database\Model', array('id'));
        
        $model
            ->expects($this->any())
            ->method('id')
            ->will($this->returnValue($id));
        
        $model->setIsDeleted($isDeleted);
        
        return $model;
    }
    
    protected function prepareSubloader()
    {
        $models = array();
        for($i=0; $i<5; $i++)
            $models[] = $this->model($i, $i==1);
        
        $this->subloader
                    ->expects($this->any())
                    ->method('offsetExists')
                    ->will($this->returnCallback(function($offset) use($models) {
                        return array_key_exists($offset, $models);
                    }));
        
        $this->subloader
                    ->expects($this->any())
                    ->method('getByOffset')
                    ->will($this->returnCallback(function($offset) use($models) {
                        if(!array_key_exists($offset, $models))
                            throw new \Exception();
                        return $models[$offset];
                    }));
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Proxy\Editable($this->loaders, $this->subloader);
    }
}