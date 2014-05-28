<?php

namespace PHPixieTests\ORM\Loaders\Loader\Preloadable\Repository;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Preloadable\Repository\ReusableStep
 */
class ReusableStepTest extends \PHPixieTests\ORM\Loaders\Loader\Preloadable\RepositoryTest
{
    protected $reusableResultStep;
    
    public function setUp()
    {
        $this->reusableResultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        parent::setUp();
    }
    
    /**
     * @covers ::resultStep
     */
    public function testResultStep()
    {
        $this->assertEquals($this->reusableResultStep, $this->loader->resultStep());
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testNotFoundException()
    {
        $this->reusableResultStep
                ->expects($this->once())
                ->method('getByOffset')
                ->with(99)
                ->will($this->returnCallback(function(){
                    throw new \Exception;
                }));
        $this->setExpectedException('\Exception');
        $this->loader->getByOffset(99);
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Preloadable\Repository\ReusableStep($this->loaders, $this->repository, $this->reusableResultStep);
    }
    
    protected function preparePreloadableModels(){
        foreach($this->preloadableModels as $key => $model) {
            $this->method($this->reusableResultStep, 'getByOffset', $this->data[$key], array($key), $key);
            $this->method($this->repository, 'load', $model, array($this->data[$key]), $key);
        }
    }
    
    
}