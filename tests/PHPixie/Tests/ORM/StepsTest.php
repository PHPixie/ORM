<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps
 */
class StepsTest extends \PHPixie\Test\Testcase
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
     * @covers ::resultFilter
     */
    public function testResultFilter()
    {
        $result = $this->abstractMock('\PHPixie\ORM\Steps\Result');
        $fields = array('fairy', 'trixie');
        
        $filter = $this->steps->resultFilter($result, $fields);
        $this->assertInstance($filter, '\PHPixie\ORM\Steps\Result\Filter', array(
            'result' => $result,
            'fields' => $fields
        ));
    }
    
    /**
     * @covers ::in
     */
    public function testIn()
    {
        $container = $this->quickMock('\PHPixie\ORM\Builder\Container');
        $resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
        $step = $this->steps->in($container, 'fairy', $resultStep, 'pixie');
        
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\In', array(
            'placeholderContainer' => $container,
            'placeholderField'     => 'fairy',
            'resultStep'           => $resultStep,
            'resultField'          => 'pixie',
        ));
    }
    
    /**
     * @covers ::pivotCartesian
     */
    public function testPivotCartesian()
    {
        $fields = array('fairy', 'trixie');
        $reusltFilters = array(
            $this->quickMock('\PHPixie\ORM\Steps\ResultFilter')
        );
        
        $step = $this->steps->pivotCartesian($fields, $reusltFilters);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Pivot\Cartesian', array(
            'fields'        => $fields,
            'resultFilters' => $reusltFilters
        ));
    }
    
    /**
     * @covers ::batchInsert
     */
    public function testBatchInsert()
    {
        $query = $this->getDatabaseQuery();
        $dataStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Insert\Data');
            
        $queryPlanner = $this->quickMock('\PHPixie\Database\Planners\Planner\Query');
        $this->method($this->planners, 'query', $queryPlanner, array(), 0);
        
        $step = $this->steps->batchInsert($query, $dataStep);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Query\Insert\Batch', array(
            'queryPlanner' => $queryPlanner,
            'query'        => $query,
            'dataStep'     => $dataStep,
        ));
    }
    
    /**
     * @covers ::uniqueDataInsert
     */
    public function testUniqueDataInsert()
    {
        $dataStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Insert\Data');
        $query = $this->getDatabaseQuery();
            
        $step = $this->steps->uniqueDataInsert($dataStep, $query);
        $this->assertInstance($step, '\PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data\Unique', array(
            'dataStep'     => $dataStep,
            'selectQuery'  => $query,
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