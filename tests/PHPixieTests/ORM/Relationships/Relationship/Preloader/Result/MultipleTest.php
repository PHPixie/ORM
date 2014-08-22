<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Preloader\Result;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Multiple
 */
abstract class MultipleTest extends \PHPixieTests\ORM\Relationships\Relationship\Preloader\ResultTest
{
    protected $map = array(
        '1' => array(3, 4, 5),
        '2' => array(6, 7),
    );
    
    public function setUp()
    {
        for($i=1; $i<3; $i++)
            $this->models[$i] = $this->getModel();
        
        for($i=3; $i<8; $i++)
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
            $ids = $this->map[$modelId];
            
            $loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
            $this->method($this->loaders, 'multiplePreloader', $loader, array($this->preloader, $ids), 0);
            
            $editable = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
            $this->method($this->loaders, 'editableProxy', $editable, array($loader), 1);
            
            $this->assertEquals($editable, $this->preloader->valueFor($model));
        }
    }
}