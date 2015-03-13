<?php

namespace PHPixie\Tests\ORM\Data\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Type\Implementation
 */
abstract class ImplementationTest extends \PHPixie\Test\Testcase
{
    protected $type;
    
    public function setUp()
    {
        $this->type = $this->getType();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSetReturn()
    {
        $this->assertEquals($this->type, $this->type->set('test', 5));
    }
    
    public abstract function testData();
    
    abstract protected function getType();
}