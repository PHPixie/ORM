<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners
 */
class PlannersTest extends \PHPixieTests\AbstractORMTest
{
    protected $ormBuilder;
    protected $planners;
    
    protected $steps;
    protected $database;
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        
        $this->planners = new \PHPixie\ORM\Planners($this->ormBuilder);
        
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->method($this->ormBuilder, 'steps', $this->steps, array());
        
        $this->database = $this->quickMock('\PHPixie\ORM\Database');
        $this->method($this->ormBuilder, 'database', $this->database, array());
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::document
     * @covers ::<protected>
     */
    public function testDocument()
    {
        $planner = $this->planners->document();
        $this->assertSame($planner, $this->planners->document());
        
        $this->assertInstance($planner, '\PHPixie\ORM\Planners\Planner\Document', array(
            
        ));
    }
    
    /**
     * @covers ::in
     * @covers ::<protected>
     */
    public function testIn()
    {
        $planner = $this->planners->in();
        $this->assertSame($planner, $this->planners->in());
        
        $this->assertInstance($planner, '\PHPixie\ORM\Planners\Planner\In', array(
            'steps' => $this->steps
        ));
    }
    
    /**
     * @covers ::pivot
     * @covers ::<protected>
     */
    public function testPivot()
    {
        $planner = $this->planners->pivot();
        $this->assertSame($planner, $this->planners->pivot());
        
        $this->assertInstance($planner, '\PHPixie\ORM\Planners\Planner\Pivot', array(
            'planners' => $this->planners,
            'steps'    => $this->steps,
            'database' => $this->database,
        ));
    }
    
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $planner = $this->planners->query();
        $this->assertSame($planner, $this->planners->query());
        
        $this->assertInstance($planner, '\PHPixie\ORM\Planners\Planner\Query');
    }
    
    /**
     * @covers ::update
     * @covers ::<protected>
     */
    public function testUpdate()
    {
        $planner = $this->planners->update();
        $this->assertSame($planner, $this->planners->update());
        
        $this->assertInstance($planner, '\PHPixie\ORM\Planners\Planner\Update', array(
            'steps'    => $this->steps
        ));
    }
}