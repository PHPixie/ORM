<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps
 */
class StepsTest extends \PHPixieTests\AbstractORMTest
{
    protected $ormBuilder;
    
    protected $steps;
    
    protected $planners;
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        
        $this->steps = new \PHPixie\ORM\Steps($this->ormBuilder);
        
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->method($this->ormBuilder, 'planners', $this->planners, array());
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
        $query = $this->getDatabaseQuery();
        $step = $this->steps->query($query);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Query', array(
            'query' => $query
        ));
    }
    
    /**
     * @covers ::count
     */
    public function testCountQuery()
    {
        $query = $this->getDatabaseQuery();
        $step = $this->steps->count($query);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Query\Count', array(
            'query' => $query
        ));
    }
    
    /**
     * @covers ::iteratorResult
     */
    public function testIteratorResult()
    {
        $query = $this->getDatabaseQuery();
        $step = $this->steps->iteratorResult($query);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Query\Result\Iterator', array(
            'query' => $query
        ));
    }
    
    /**
     * @covers ::reusableResult
     */
    public function testReusableResult()
    {
        $query = $this->getDatabaseQuery();
        $step = $this->steps->reusableResult($query);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Query\Result\Reusable', array(
            'query' => $query
        ));
    }
    
    /**
     * @covers ::in
     */
    public function testIn()
    {
        $placeholder = $this->quickMock('\PHPixie\Database\Conditions\Condition\Collection\Placeholder');
        $resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
        $step = $this->steps->in($placeholder, 'fairy', $resultStep, 'pixie');
        
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\In', array(
            'placeholder'      => $placeholder,
            'placeholderField' => 'fairy',
            'resultStep'       => $resultStep,
            'resultField'      => 'pixie',
        ));
    }
    
    /**
     * @covers ::pivotCartesian
     */
    public function testPivotCartesian()
    {
        $reusltFilters = array(
            $this->quickMock('\PHPixie\ORM\Steps\ResultFilter')
        );
        
        $step = $this->steps->pivotCartesian($reusltFilters);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Pivot\Cartesian', array(
            'resultFilters' => $reusltFilters
        ));
    }
    
    /**
     * @covers ::pivotInsert
     */
    public function testPivotInsert()
    {
        $query = $this->getDatabaseQuery();
        $cartesianStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Pivot\Cartesian');
        $fields = array('fairy', 'trixie');
            
        $queryPlanner = $this->quickMock('\PHPixie\Database\Planners\Planner\Query');
        $this->method($this->planners, 'query', $queryPlanner, array(), 0);
        
        $step = $this->steps->pivotInsert($query, $fields, $cartesianStep);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Pivot\Insert', array(
            'queryPlanner'  => $queryPlanner,
            'insertQuery'   => $query,
            'fields'        => $fields,
            'cartesianStep' => $cartesianStep,
        ));
    }
    
    /**
     * @covers ::updateMap
     */
    public function testUpdateMap()
    {
        $updateQuery = $this->getDatabaseQuery();
        $map = array('pixie' => 'fairy');
        $resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
        
        $step = $this->steps->updateMap($updateQuery, $map, $resultStep);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Update\Map', array(
            'updateQuery' => $updateQuery,
            'map'         => $map,
            'resultStep'  => $resultStep
        ));
    }
    
    protected function getDatabaseQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Query');
    }

}