<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions
 */
class ConditionsTest extends \PHPixieTests\AbstractORMTest
{
    protected $ormBuilder;
    
    protected $conditions;
    
    protected $maps;
    protected $relationshipMap;
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->conditions = new \PHPixie\ORM\Conditions($this->ormBuilder);
        $this->maps = $this->quickMock('\PHPixie\ORM\Maps');
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Relationship');
        
        $this->method($this->ormBuilder, 'maps', $this->maps, array());
        $this->method($this->maps, 'relationship', $this->relationshipMap, array());
    }
    
    
    /**
     * @covers ::relatedToGroup
     * @covers ::<protected>
     */
    public function testRelatedToGroup()
    {
        $group = $this->conditions->relatedToGroup('pixie');
        $this->assertInstance($group, '\PHPixie\ORM\Conditions\Condition\Collection\Group');
        $this->assertSame('pixie', $group->relationship());
    }
    
    /**
     * @covers ::in
     * @covers ::<protected>
     */
    public function testIn()
    {
        $modelName = 'pixie';
        $item = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In\Item');
        $this->method($item, 'modelName', $modelName, array(), 0);
        
        $in = $this->conditions->in($modelName, $item);
        $this->assertInstance($in, '\PHPixie\ORM\Conditions\Condition\In');
        $this->assertSame($modelName, $in->modelName());
        $this->assertSame(array($item), $in->items());
    }
    
    protected function conditions()
    {
        return new \PHPixie\ORM\Conditions($this->ormBuilder);
    }
}