<?php

namespace PHPixieTests\ORM\Values;

/**
 * @coversDefaultClass \PHPixie\ORM\Values\Update\Remove
 */
class RemoveTest extends \PHPixieTests\AbstractORMTest
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