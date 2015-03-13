<?php

namespace PHPixie\Tests\ORM\Conditions\Condition\Collection\RelatedTo;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Condition\Collection\RelatedTo\Group
 */
class GroupTest extends \PHPixie\Tests\ORM\Conditions\Condition\Collection\GroupTest
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
     * @covers ::setRelationship
     * @covers ::<protected>
     */
    public function testRelationship()
    {
        $this->assertSame($this->relationship, $this->condition->relationship());
        $this->condition->setRelationship('test');
        $this->assertSame('test', $this->condition->relationship());
    }
    
    protected function condition()
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\RelatedTo\Group($this->relationship);
    }
}