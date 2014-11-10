<?php

namespace PHPixieTests\ORM\Models\Type\Database\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Type\Database\Implementation\Query
 */
abstract class QueryTest extends \PHPixieTests\AbstractORMTest
{
    /**
     * @covers ::limit
     * @covers ::getLimit
     * @covers ::clearLimit
     * @covers ::<protected>
     */
    public function testLimit()
    {
        $this->assertSame(null, $this->query->getLimit());
        
        $this->query->limit(10);
        $this->assertSame(10, $this->query->getLimit());
        
        $this->query->clearLimit();
        $this->assertSame(null, $this->query->getLimit());
        
        $this->invalidArgumentsTest(array($this->query, 'limit'), array(
            array('test'),
            array(null)
        ));
    }
    
    /**
     * @covers ::offset
     * @covers ::getOffset
     * @covers ::clearOffset
     * @covers ::<protected>
     */
    public function testOffset()
    {
        $this->assertSame(null, $this->query->getOffset());
        
        $this->query->offset(10);
        $this->assertSame(10, $this->query->getOffset());
        
        $this->query->clearOffset();
        $this->assertSame(null, $this->query->getOffset());
        
        $this->invalidArgumentsTest(array($this->query, 'offset'), array(
            array('test'),
            array(null)
        ));
    }
    
    /**
     * @covers ::orderAscendingBy
     * @covers ::orderDescendingBy
     * @covers ::getOrderBy
     * @covers ::clearOrderBy
     * @covers ::<protected>
     */
    public function testGroupBy()
    {
        $this->assertSame(array(), $this->query->getOrderBy());
        
        $this->query->orderAscendingBy('id');
        $this->query->orderDescendingBy('id');
    }
    
    protected function invalidArgumentsTest($callback, $paramSets)
    {
        foreach($paramSets as $params) {
            $except = false;
            try {
                call_user_func_array($callback, $params);
            } catch('\PHPixie\ORM\Exception\Query') {
                $except = true;
            }
            $this->assertSame(true, $except);
        }
    }
}