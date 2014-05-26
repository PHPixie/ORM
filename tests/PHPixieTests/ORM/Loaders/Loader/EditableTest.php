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
     * @covers ::removeAll
     * @covers ::offsetExists
     * @covers ::getByOffset
     */
    public function testEdit()
    {
        $this->prepareSubloader();
        $this->assertItems(array(0, 2));
        $model = $this->model(3);
        $this->loader->add(array($model));
        $this->loader->offsetExists(0);
        /*$this->assertItems(array(0, 2, 3));*/
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
        $model = $this->quickMock('\PHPixie\ORM\Model\Repository\Database\Model');
        
        $model
            ->expects($this->any())
            ->method('id')
            ->will($this->returnValue($id));
        
        $model
            ->expects($this->any())
            ->method('isDeleted')
            ->will($this->returnValue($isDeleted));
        
        return $model;
    }
    
    protected function prepareSubloader()
    {
        $models = array();
        for($i=0; $i<3; $i++)
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