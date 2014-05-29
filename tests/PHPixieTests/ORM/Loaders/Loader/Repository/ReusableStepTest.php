<?php

namespace PHPixieTests\ORM\Loaders\Loader\Repository;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository\ReusableStep
 */
class ReusableStepTest extends \PHPixieTests\ORM\Loaders\Loader\RepositoryTest
{
    protected $reusableResultStep;
    
    public function setUp()
    {
        $this->reusableResultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        parent::setUp();
        
        $this->reusableResultStep
                ->expects($this->any())
                ->method('offsetExists')
                ->will($this->returnCallBack(function($offset){
                    return $offset < 5;
                }));
        $data = $this->data;
        $this->reusableResultStep
                ->expects($this->any())
                ->method('getByOffset')
                ->will($this->returnCallBack(function($offset) use($data){
                    if($offset > 4)
                        throw new \Exception;
                    return $data[$offset];
                }));
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
        return new \PHPixie\ORM\Loaders\Loader\Repository\ReusableStep($this->loaders, $this->repository, $this->reusableResultStep);
    }
    
    
}