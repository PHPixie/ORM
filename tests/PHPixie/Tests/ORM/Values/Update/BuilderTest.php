<?php

namespace PHPixie\Tests\ORM\Values;

/**
 * @coversDefaultClass \PHPixie\ORM\Values\Update\Builder
 */
class BuilderTest extends \PHPixie\Tests\ORM\Values\UpdateTest
{
    protected $query;
    
    public function setUp()
    {
        $this->query  = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
        parent::setUp();
    }
    
    /**
     * @covers \PHPixie\ORM\Values\Update::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::plan
     * @covers ::<protected>
     */
    public function testPlan()
    {
        $plan = $this->getPlan();
        $this->method($this->query, 'planUpdateValue', $plan, array(), 0);
        $this->assertSame($plan, $this->update->plan());
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecute()
    {
        $plan = $this->getPlan();
        $this->method($this->query, 'planUpdateValue', $plan, array(), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->update->execute();
    }
    
    protected function update()
    {
        return new \PHPixie\ORM\Values\Update\Builder($this->values, $this->query);
    }
}