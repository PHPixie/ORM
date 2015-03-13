<?php

namespace PHPixie\Tests\ORM\Values;

/**
 * @coversDefaultClass \PHPixie\ORM\Values\Update\Remove
 */
class RemoveTest extends \PHPixie\Test\Testcase
{
    protected $remove;
    
    public function setUp()
    {
        $this->remove = new \PHPixie\ORM\Values\Update\Remove();
    }
    
    /**
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
}