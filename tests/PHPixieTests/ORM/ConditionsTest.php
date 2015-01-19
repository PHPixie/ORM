<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions
 */
class ConditionsTest extends \PHPixieTests\Database\ConditionsTest
{
    protected $operatorClass    = '\PHPixie\ORM\Conditions\Condition\Field\Operator';
    protected $groupClass       = '\PHPixie\ORM\Conditions\Condition\Collection\Group';
    protected $placeholderClass = '\PHPixie\ORM\Conditions\Condition\Collection\Placeholder';
    protected $containerClass   = '\PHPixie\ORM\Conditions\Builder\Container';
    
    protected $relatedToGroupClass = '\PHPixie\ORM\Conditions\Condition\Collection\RelatedTo\Group';
    protected $inClass             = '\PHPixie\ORM\Conditions\Condition\In';
    
    /**
     * @covers ::relatedToGroup
     * @covers ::<protected>
     */
    public function testRelatedToGroup()
    {
        $group = $this->conditions->relatedToGroup('pixie');
        $this->assertInstanceOf($this->relatedToGroupClass, $group);
        $this->assertSame('pixie', $group->relationship());
    }
    
    /**
     * @covers ::in
     * @covers ::<protected>
     */
    public function testIn()
    {
        $item = $this->quickMock('\PHPixie\ORM\Relationship\Conditions\Condition\In\Item', array());
        $items = array($item);
        $in = $this->conditions->in($items);
        $this->assertInstanceOf($this->inClass, $in);
        $this->assertSame($items, $in->items());
    }
    
    protected function conditions()
    {
        return new \PHPixie\ORM\Conditions();
    }
}