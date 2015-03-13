<?php

namespace PHPixie\Tests\ORM\Mappers;

interface QueryStub extends \PHPixie\Database\Query\Type\Update\Incrementable,
                            \PHPixie\Database\Query\Type\Update\Unsetable
{
    
}

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Update
 */
class UpdateTest extends \PHPixie\Test\Testcase
{
    protected $updateMapper;

    public function setUp()
    {
        $this->updateMapper = new \PHPixie\ORM\Mappers\Update();
    }
    
    /**
     * @covers ::map
     * @cvoers ::<protected>
     */
    public function testMap()
    {
        $set = array('a' => 1, 'b' => 2);
        
        $increments = array('c' => 1, 'd' => -2);
        $updateIncrements = array();
        foreach($increments as $key => $amount) {
            $updateIncrements[$key] = $this->getIncrement($amount);
        }
        
        $remove = array('e', 'f');
        $updateRemove = array();
        foreach($remove as $field) {
            $updateRemove[$field] = $this->getRemove();
        }
        
        $query = $this->getUpdateQuery('all');
        $update = $this->getUpdate(array_merge($set, $updateIncrements, $updateRemove));
        $this->method($query, 'set', null, array($set), 0);
        $this->method($query, 'increment', null, array($increments), 1);
        $this->method($query, '_unset', null, array($remove), 2);
        $this->updateMapper->map($query, $update);
        
    }
    
    /**
     * @covers ::map
     * @covers ::<protected>
     */
    public function testMapException()
    {
        $values = array(
            $this->getIncrement(5),
            $this->getRemove(),
        );
        
        $query = $this->getUpdateQuery();
        
        foreach($values as $value) {
            $update = $this->getUpdate(array('a' => $value));
            
            $except = false;
            try{
                $this->updateMapper->map($query, $update);
            }catch(\PHPixie\ORM\Exception\Mapper $e){
                $except = true;
            }
            
            $this->assertEquals(true, $except);
        }
    }
    
    protected function getUpdateQuery($type = null)
    {
        if($type == 'all')
            return $this->abstractMock('\PHPixie\Tests\ORM\Mappers\QueryStub');
            
        if($type !== null)
            $type = "\\".$type;
        
        return $this->abstractMock('\PHPixie\Database\Query\Type\Update'.$type);
    }
    
    protected function getUpdate($updates)
    {
        $update = $this->quickMock('\PHPixie\ORM\Values\Update');
        $this->method($update, 'updates', $updates, array());
        return $update;
    }
    
    protected function getIncrement($amount)
    {
        $increment = $this->quickMock('\PHPixie\ORM\Values\Update\Increment');
        $this->method($increment, 'amount', $amount, array());
        return $increment;
    }
    
    protected function getRemove()
    {
        return $this->quickMock('\PHPixie\ORM\Values\Update\Remove');
    }
}