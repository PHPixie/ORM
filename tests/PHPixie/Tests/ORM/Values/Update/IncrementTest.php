<?php

namespace PHPixie\Tests\ORM\Values;

/**
 * @coversDefaultClass \PHPixie\ORM\Values\Update\Increment
 */
class IncrementTest extends \PHPixie\Test\Testcase
{
    protected $amount = 5;
    protected $increment;
    
    public function setUp()
    {
        $this->increment = new \PHPixie\ORM\Values\Update\Increment($this->amount);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::amount
     * @covers ::<protected>
     */
    public function testAmount()
    {
        $this->assertEquals($this->amount, $this->increment->amount());
    }
}