<?php

namespace PHPixieTests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Editable
 */
class EditableTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $subloader;
    protected $ids = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');
    
    public function setUp()
    {
        $this->subloader = $this->quickMock('\PHPixie\ORM\Loaders\Loader');
        parent::setUp();
    }
    
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
        /*
        $this->assertItems(array(0, 2, 3, 4));
        
        $model5 = $this->model(5);
        $this->loader->add(array($model5));
        $this->assertItems(array(0, 2, 3, 4, 5));
        
        $this->loader->add(array($this->model(6)));
        $this->assertItems(array(0, 2, 3, 4, 5, 6));
        
        $model5->setIsDeleted(true);
        $this->assertItems(array(0, 2, 3, 4, 6));
        
        $model0 = $this->loader->getByOffset(0);
        $model0->setIsDeleted(true);
        $this->assertItems(array(2, 3, 4, 6));
        */
        $this->loader->remove(array($this->loader->getByOffset(2)));
        $this->assertItems(array(2, 3, 6));
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
        print_r($ids);
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
        return new \PHPixie\ORM\Loaders\Loader\Editable($this->loaders, $this->subloader);
    }
}