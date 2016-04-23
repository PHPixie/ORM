<?php

namespace PHPixie\Tests\ORM\Wrappers\Type\Database;

/**
 * @coversDefaultClass \PHPixie\ORM\Wrappers\Type\Database\Query
 */
class QueryTest extends \PHPixie\Tests\ORM\Conditions\Builder\ProxyTest
{
    protected $query;
    protected $wrapper;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->query   = $this->builder;
        $this->wrapper = $this->proxy;
        
    }
    
    /**
     * @covers \PHPixie\ORM\Conditions\Builder\Proxy::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::<public>
     * @covers ::<protected>
     */
    public function testForwarding()
    {
        $methods = array(
            array('modelName', true, array()),
            array('limit', false, array(1)),
            array('getLimit', true, array()),
            array('clearLimit', false, array()),
            array('offset', false, array(1)),
            array('getOffset', true, array()),
            array('clearOffset', false, array()),
            array('orderAscendingBy', false, array('a')),
            array('orderDescendingBy', false, array('a')),
            array('getOrderBy', true, array()),
            array('clearOrderBy', false, array()),
            array('getConditions', true, array()),
            array('planFind', true, array()),
            array('find', true, array()),
            array('findOne', true, array()),
            array('planFind', true, array(array('test'))),
            array('find', true, array(array('test'))),
            array('findOne', true, array(array('test'))),
            array('planDelete', true, array()),
            array('delete', false, array()),
            array('planUpdate', true, array(1)),
            array('update', false, array(1)),
            array('getUpdateBuilder', true, array()),
            array('planUpdateValue', true, array(array('test'))),
            array('planCount', true, array()),
            array('count', true, array()),
            array('getRelationshipProperty', true, array('test')),
        );
        
        foreach($methods as $set) {
            $method = $set[0];
            
            if($set[1]) {
                $return = 'test';
                $expect = 'test';
            }else{
                $return = null;
                $expect = $this->wrapper;
            }
            
            $params = isset($set[3]) ? $set[3] : $set[2];
            $this->method($this->query, $method, $return, $params, 0);
            $this->assertSame($expect, call_user_func_array(array($this->wrapper, $method), $set[2]));
        }

    }
    
    /**
     * @covers ::__get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $this->method($this->query, '__get', 5, array('test'), 0);
        $this->assertSame(5, $this->wrapper->test);
    }
    
    /**
     * @covers ::__call
     * @covers ::<protected>
     */
    public function testCall()
    {
        $this->method($this->query, '__call', 5, array('test', array(4)), 0);
        $this->assertSame(5, $this->wrapper->test(4));
    }
    
    protected function getBuilder()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function proxy()
    {
        return new \PHPixie\ORM\Wrappers\Type\Database\Query($this->builder);
    }
}