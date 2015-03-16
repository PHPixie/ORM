<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners
 */
class PlannersTest extends \PHPixie\Test\Testcase
{
    protected $ormBuilder;
    protected $planners;
    
    protected $dependencies = array();
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        
        $this->planners = new \PHPixie\ORM\Planners($this->ormBuilder);
        
        foreach(array('conditions', 'database', 'mappers', 'steps') as $name) {
            $instance = $this->quickMock('\PHPixie\ORM\\'.ucfirst($name));
            $this->method($this->ormBuilder, $name, $instance, array());
            $this->dependencies[$name] = $instance;
        }
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
            'conditions' => $this->dependencies['conditions'],
            'mappers'    => $this->dependencies['mappers'],
            'steps'      => $this->dependencies['steps'],
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
            'steps'    => $this->dependencies['steps'],
            'database' => $this->dependencies['database'],
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
            'steps'    => $this->dependencies['steps']
        ));
    }
}