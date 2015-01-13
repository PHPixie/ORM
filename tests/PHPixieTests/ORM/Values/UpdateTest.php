<?php

namespace PHPixieTests\ORM\Values;

/**
 * @coversDefaultClass \PHPixie\ORM\Values\Update
 */
class UpdateTest extends \PHPixieTests\AbstractORMTest
{
    protected $values;
    protected $query;
    protected $update;
    
    public function setUp()
    {
        $this->values = $this->quickMock('\PHPixie\ORM\Values');
        $this->query  = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
        $this->update = $this->update();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::set
     * @covers ::increment
     * @covers ::remove
     * @covers ::updates
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->assertEquals(array(), $this->update->updates());
        
        $this->assertSame($this->update, $this->update->set('a', 1));
        $this->update->set('a', 2);
        $this->update->set('b', 3);
        
        $increment = $this->getValue('increment');
        $this->method($this->values, 'updateIncrement', $increment, array(-3), 0);
        $this->assertSame($this->update, $this->update->increment('c', -3));
        
        $remove = $this->getValue('remove');
        $this->method($this->values, 'updateRemove', $remove, array(), 0);
        $this->assertSame($this->update, $this->update->remove('d'));
        
        $expects = array(
            'a' => 2,
            'b' => 3,
            'c' => $increment,
            'd' => $remove
        );
        
        $this->assertEquals($expects, $this->update->updates());
    }
    
    /**
     * @covers ::plan
     * @covers ::<protected>
     */
    public function testPlan()
    {
        $plan = $this->getPlan();
        $this->method($this->query, 'planUpdateBuilder', $plan, array(), 0);
        $this->assertSame($plan, $this->update->plan());
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecute()
    {
        $plan = $this->getPlan();
        $this->method($this->query, 'planUpdateBuilder', $plan, array(), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->update->execute();
    }
    
    protected function getPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Query');;
    }
    
    protected function getValue($type)
    {
        return $this->abstractMock('\PHPixie\ORM\Values\Update\\'.ucfirst($type));
    }
    
    protected function update()
    {
        return new \PHPixie\ORM\Values\Update($this->values, $this->query);
    }
}