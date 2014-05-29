<?php

namespace PHPixieTests;

abstract class AbstractORMTest extends \PHPUnit_Framework_TestCase
{
    protected function quickMock($class, $methods = array())
    {
        return $this->getMock($class, $methods, array(), '', false);
    }
    
    protected function abstractMock($class, $methods = array())
    {
        if(empty($methods)){
            $reflection = new \ReflectionClass($class);
            foreach($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method)
                $methods[]=$method->getName();
        }
        
        return $this->getMockForAbstractClass($class, array(), '', false, false, true, $methods);
    }
    
    protected function method($mock, $method, $return, $with = null, $at = null) {
        $method = $mock
            ->expects($at === null ? $this->any() : $this->at($at))
            ->method($method);
        
        if ($with !== null)
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
