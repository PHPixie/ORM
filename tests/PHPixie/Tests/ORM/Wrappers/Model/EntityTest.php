<?php

namespace PHPixie\Tests\ORM\Wrappers\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Wrappers\Model\Entity
 */
abstract class EntityTest extends \PHPixie\Test\Testcase
{
    protected $entity;
    protected $wrapper;
    
    protected $methods = array(
        array('modelName', true, array()),
        array('asObject', true, array(), array(false)),
        array('asObject', true, array(true), array(true)),
        array('data', true, array()),
        array('getRelationshipProperty', true, array('a'), array('a', true)),
        array('getRelationshipProperty', true, array('a', false), array('a', false)),
        array('getField', true, array('a')),
        array('getField', true, array('a', 5)),
        array('getRequiredField', true, array('a')),
        array('setField', false, array('a', 5)),
    );
    public function setUp()
    {
        $this->entity  = $this->entity();
        $this->wrapper = $this->wrapper();
    }
    
    /**
     * @covers ::<public>
     * @covers ::<protected>
     */
    public function testForwarding()
    {
        foreach($this->methods as $set) {
            $method = $set[0];
            
            if($set[1]) {
                $return = 'test';
                $expect = 'test';
            }else{
                $return = null;
                $expect = $this->wrapper;
            }
            
            $params = isset($set[3]) ? $set[3] : $set[2];
            $this->method($this->entity, $method, $return, $params, 0);
            $this->assertSame($expect, call_user_func_array(array($this->wrapper, $method), $set[2]));
        }

    }
    
    /**
     * @covers ::__get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $this->method($this->entity, '__get', 5, array('test'), 0);
        $this->assertSame(5, $this->wrapper->test);
    }
    
    /**
     * @covers ::__set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->method($this->entity, '__set', null, array('test', 5), 0);
        $this->wrapper->test = 5;
    }
    
    /**
     * @covers ::__call
     * @covers ::<protected>
     */
    public function testCall()
    {
        $this->method($this->entity, '__call', 5, array('test', array(4)), 0);
        $this->assertSame(5, $this->wrapper->test(4));
    }
    
    abstract protected function entity();
    abstract protected function wrapper();
    
}