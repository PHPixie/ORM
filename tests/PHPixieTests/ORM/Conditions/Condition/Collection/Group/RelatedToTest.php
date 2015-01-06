<?php

namespace PHPixieTests\ORM\Conditions\Condition\Collection\Group;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Condition\Collection\Group\RelatedTo
 */
class RelatedToTest extends \PHPixieTests\ORM\Conditions\Condition\Collection\GroupTest
{
    protected $relationship = 'pixie';
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::relationship
     * @covers ::getRelationship
     * @covers ::<protected>
     */
    public function testRelationship()
    {
    
    }
    
    protected function condition()
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\Group\RelatedTo($this->relationship);
    }
}