<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps
 */
class StepsTest extends \PHPixieTests\AbstractORMTest
{
    protected $planners;
    protected $steps;
    
    public function setUp()
    {
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners', array('query'));
        $this->steps = new \PHPixie\ORM\Steps($this->planners);
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::query
     */
    public function testQuery()
    {
        $query = $this->quickMock('\PHPixie\Database\Query');
        $step = $this->steps->query($query);
        $this->assertInstanceOf('\PHPixie\ORM\Steps\Step\Query', $step);
        $this->assertAttributeEquals($query, 'query', $step);
    }
    
    /**
     * @covers ::result
     */
    public function testResult()
    {
        $query = $this->quickMock('\PHPixie\Database\Query');
        $step = $this->steps->result($query);
        $this->assertInstanceOf('\PHPixie\ORM\Steps\Step\Query\Result\SingleUse', $step);
        $this->assertAttributeEquals($query, 'query', $step);
    }
    
    /**
     * @covers ::reusableResult
     */
    public function testReusableResult()
    {
        $query = $this->quickMock('\PHPixie\Database\Query');
        $step = $this->steps->reusableResult($query);
        $this->assertInstanceOf('\PHPixie\ORM\Steps\Step\Query\Result\Reusable', $step);
        $this->assertAttributeEquals($query, 'query', $step);
    }
    
    /**
     * @covers ::in
     */
    public function testIn()
    {
        $placeholder = $this->quickMock('\PHPixie\Database\Conditions\Condition\Placeholder');
        $resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
        $step = $this->steps->in($placeholder, 'fairy', $resultStep, 'pixie');
        $this->assertInstanceOf('\PHPixie\ORM\Steps\Step\In', $step);
        $this->assertAttributeEquals($placeholder, 'placeholder', $step);
        $this->assertAttributeEquals('fairy', 'placeholderField', $step);
        $this->assertAttributeEquals($resultStep, 'resultStep', $step);
        $this->assertAttributeEquals('pixie', 'resultField', $step);
    }
    
    /**
     * @covers ::pivotCartesian
     */
    public function testPivotCartesian()
    {
        $step = $this->steps->pivotCartesian(array(5));
        $this->assertInstanceOf('\PHPixie\ORM\Steps\Step\Pivot\Cartesian', $step);
        $this->assertAttributeEquals(array(5), 'resultFilters', $step);
    }
    
    /**
     * @covers ::pivotInsert
     */
    public function testPivotInsert()
    {
        $queryPlanner = $this->quickMock('\PHPixie\Database\Planners\Planner\Query');
        $this->method($this->planners, 'query', $queryPlanner, 0);
        
        $query = $this->quickMock('\PHPixie\Database\Query');
        $cartesian = $this->quickMock('\PHPixie\ORM\Steps\Step\Pivot\Cartesian');
        $step = $this->steps->pivotInsert($query, array('fairy', 'trixie'), $cartesian);
        
        $this->assertInstanceOf('\PHPixie\ORM\Steps\Step\Pivot\Insert', $step);
        $this->assertAttributeEquals($queryPlanner, 'queryPlanner', $step);
        $this->assertAttributeEquals($query, 'insertQuery', $step);
        $this->assertAttributeEquals(array('fairy', 'trixie'), 'fields', $step);
        $this->assertAttributeEquals($cartesian, 'cartesianStep', $step);
    }

}