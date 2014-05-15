<?php

namespace PHPixieTests;

class DatabaseResultStub extends \ArrayIterator
{
    public function asArray()
    {
        return $this->getArrayCopy();
    }
}

abstract class AbstractORMTest extends \PHPUnit_Framework_TestCase
{
    protected function quickMock($class, $methods)
    {
        return $this->getMock($class, $methods, array(), '', false);
    }
    
    protected function method($mock, $method, $return, $at = null, $setWith = false, $with = null) {
        $method = $mock
            ->expects($at === null ? $this->any() : $this->at($at))
            ->method($method);
        
        if ($setWith)
            $method = call_user_func_array(array($method, 'with'), $with);
        
        if (is_callable($return) && !is_array($return)) {
            $method->will($this->returnCallback($return));
        }else
            $method->will($this->returnValue($return));
    }
    
    protected function valueObject()
    {
        return new \stdClass;
    }
    
    protected function databaseResultStub($data)
    {
        return new DatabaseResultStub($data);
    }
    
    protected function assertException($callback, $exceptionClass)
    {
        $except = false;
        try{
            $callback();
        }catch(\Exception $e){
            $except = $e instanceof $exceptionClass;
        }
        
        $this->assertEquals(true, $except);
    }
}
