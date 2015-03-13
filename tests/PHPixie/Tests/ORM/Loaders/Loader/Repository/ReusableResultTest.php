<?php

namespace PHPixie\Tests\ORM\Loaders\Loader\Repository;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository\ReusableResult
 */
class ReusableResultTest extends \PHPixie\Tests\ORM\Loaders\Loader\RepositoryTest
{
    protected $reusableResult;
    
    public function setUp()
    {
        $this->reusableResult = $this->quickMock('\PHPixie\ORM\Steps\Result\Reusable');
        parent::setUp();
    }

    /** 
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testOffsetExists()
    {
        foreach(array(true, false) as $logic) {
            $this->method($this->reusableResult, 'offsetExists', $logic, array(3), 0);
            $this->assertSame($logic, $this->loader->offsetExists(3));
        }
    }
    
    /** 
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testGetByOffset()
    {
        $data = (object) array('name' => 'Pixie');
        $this->method($this->reusableResult, 'getByOffset', $data, array(3), 0);
        $entity = $this->prepareLoadEntity($data);
        $this->assertSame($entity, $this->loader->getByOffset(3));
    }
    
    
    /** 
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testNotFoundException()
    {
        $this->reusableResult
                ->expects($this->once())
                ->method('getByOffset')
                ->with(99)
                ->will($this->throwException(new \Exception));
        
        $this->setExpectedException('\Exception');
        $this->loader->getByOffset(99);
    }
    
    /** 
     * @covers ::reusableResult
     * @covers ::<protected>
     */
    public function testReusableResult()
    {
        $this->assertSame($this->reusableResult, $this->loader->reusableResult());
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Repository\ReusableResult(
            $this->loaders,
            $this->repository,
            $this->reusableResult
        );
    }
    
    
}