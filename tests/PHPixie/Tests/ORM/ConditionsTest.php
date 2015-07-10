<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions
 */
class ConditionsTest extends \PHPixie\Test\Testcase
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
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::container
     * @covers ::<protected>
     */
    public function testContainer()
    {
        $container = $this->conditions->container('pixie');
        $this->assertContainer($container);
        
        $container = $this->conditions->container('pixie', '>');
        $this->assertContainer($container, '>');
    }
    
    /**
     * @covers ::placeholder
     * @covers ::<protected>
     */
    public function testPlaceholder()
    {
        $placeholder = $this->conditions->placeholder('pixie');
        $this->assertInstance($placeholder, '\PHPixie\ORM\Conditions\Condition\Collection\Placeholder', array(
            'allowEmpty' => true
        ));
        $this->assertContainer($placeholder->container());
        
        $placeholder = $this->conditions->placeholder('pixie', '>', false);
        $this->assertInstance($placeholder, '\PHPixie\ORM\Conditions\Condition\Collection\Placeholder', array(
            'allowEmpty' => false
        ));
        $this->assertContainer($placeholder->container(), '>');
    }
    
    /**
     * @covers ::operator
     * @covers ::<protected>
     */
    public function testOperator()
    {
        $operator = $this->conditions->operator('id', '>', array(3));
        $this->assertInstance($operator, '\PHPixie\ORM\Conditions\Condition\Field\Operator', array(
            'field'    => 'id',
            'operator' => '>',
            'values'   => array(3),
        ));
    }
    
    /**
     * @covers ::group
     * @covers ::<protected>
     */
    public function testGroup()
    {
        $group = $this->conditions->group();
        $this->assertInstance($group, '\PHPixie\ORM\Conditions\Condition\Collection\Group');
    }
    
    /**
     * @covers ::relatedToGroup
     * @covers ::<protected>
     */
    public function testRelatedToGroup()
    {
        $group = $this->conditions->relatedToGroup('pixie');
        $this->assertInstance($group, '\PHPixie\ORM\Conditions\Condition\Collection\RelatedTo\Group');
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
    
    /**
     * @covers ::subquery
     * @covers ::<protected>
     */
    public function testSubquery()
    {
        $field = 'id';
        $subquery = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
        $subqueryField = 'pixieId';
        
        $condition = $this->conditions->subquery($field, $subquery, $subqueryField);
        $this->assertInstance($condition, '\PHPixie\ORM\Conditions\Condition\Field\Subquery');
        $this->assertSame($field, $condition->field());
        $this->assertSame($subquery, $condition->subquery());
        $this->assertSame($subqueryField, $condition->subqueryField());
    }
    
    protected function assertContainer($container, $defaultOperator = '=')
    {
        $this->assertInstance($container, '\PHPixie\ORM\Conditions\Builder\Container', array(
            'conditions'       => $this->conditions,
            'relationshipMap'  => $this->relationshipMap,
            'currentModelName' => 'pixie',
            'defaultOperator'  => $defaultOperator,
        ));
    }
    
    protected function conditions()
    {
        return new \PHPixie\ORM\Conditions($this->ormBuilder);
    }
}