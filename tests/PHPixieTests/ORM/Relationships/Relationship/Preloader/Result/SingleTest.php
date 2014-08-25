<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Preloader\Result;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Single
 */
abstract class singleTest extends \PHPixieTests\ORM\Relationships\Relationship\Preloader\ResultTest
{
    protected $map = array(
        1 => 5,
        2 => 6,
        3 => 5,
        4 => 6,
    );
    
    public function setUp()
    {
        for($i=1; $i<5; $i++)
            $this->models[$i] = $this->getModel();
        
        for($i=5; $i<7; $i++)
            $this->preloadedModels[$i] = $this->getModel();
        
        parent::setUp();
    }
    
    /**
     * @covers ::loadFor
     * @covers ::<protected>
     */
    public function testLoadFor()
    {
        $this->prepareMap();
        foreach($this->models as $modelId => $model) {
            $id = $this->map[$modelId];
            $preloaded = $this->preloadedModels[$id];
            
            $this->assertEquals($preloaded, $this->preloader->valueFor($model));
        }
    }
}