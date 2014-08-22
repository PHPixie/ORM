<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Preloader\Result
 */
abstract class ResultTest extends \PHPixieTests\ORM\Relationships\Relationship\PreloaderTest
{
    protected $models = array();
    protected $preloadedModels = array();
    
    /**
     * @covers ::getModel
     * @covers ::<protected>
     */
    public function testGetModel()
    {
        $this->prepareMap();
        $modelOffsets = array_values($this->preloadedModels);
        $this->loader
                ->expects($this->any())
                ->method('getByOffset')
                ->will($this->returnCallback(function($offset) use ($modelOffsets) {
                    return $modelOffsets[$offset];
                }));
        
        $models = array_reverse($this->preloadedModels, true);
        $i = count($models) - 1;
        foreach($models as $id => $model) {
            $this->assertEquals($model, $this->preloader->getModel($id));
            $i--;
        }
    }
    
    abstract protected function prepareMap();
}