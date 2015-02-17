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
    
    protected $ormBuilder;
    protected $maps;
    protected $relationshipMap;
    
    public function setUp()
    {
        $this->ormBuilder = $this->getMock('\PHPixie\ORM\Builder', array(), array(), '', false);
        $this->maps = $this->getMock('\PHPixie\ORM\Maps', array(), array(), '', false);
        $this->relationshipMap = $this->getMock('\PHPixie\ORM\Maps\Map\Relationship', array(), array(), '', false);

        $this->ormBuilder
            ->expects($this->any())
            ->method('maps')
            ->will($this->returnValue($this->maps));
        
        $this->maps
            ->expects($this->any())
            ->method('relationship')
            ->will($this->returnValue($this->relationshipMap));
        
        parent::setUp();
    }
    
    
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
        $modelName = 'pixie';
        $item = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In\Item', array());
        $item
            ->expects($this->any())
            ->method('modelName')
            ->with()
            ->will($this->returnValue($modelName));
        
        $in = $this->conditions->in($modelName, $item);
        $this->assertInstanceOf($this->inClass, $in);
        $this->assertSame($modelName, $in->modelName());
        $this->assertSame(array($item), $in->items());
    }
    
    protected function conditions()
    {
        return new \PHPixie\ORM\Conditions($this->ormBuilder);
    }
}