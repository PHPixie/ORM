<?php

namespace PHPixieTests\ORM\Relationships\Relationship;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Handler
 */
abstract class HandlerTest extends \PHPixieTests\AbstractORMTest
{
    protected $handler;
    protected $ormBuilder;
    protected $repositories;
    protected $planners;
    protected $plans;
    protected $steps;
    protected $loaders;
    protected $relationship;
    protected $groupMapper;
    protected $cascadeMapper;
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->repositories = $this->quickMock('\PHPixie\ORM\Repositories');
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->plans = $this->quickMock('\PHPixie\ORM\Plans');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        $this->relationship = $this->getRelationship();
        $this->groupMapper = $this->quickMock('\PHPixie\ORM\Mapper\Group');
        $this->cascadeMapper = $this->quickMock('\PHPixie\ORM\Mapper\Cascade');
        $this->handler = $this->getHandler();
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Handler::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    protected function setRepositories($repositories)
    {
        $this->repositories
                    ->expects($this->any())
                    ->method('get')
                    ->will($this->returnCallback(function($name) use($repositories){
                        return $repositories[$name]; 
                    }));
    }
    
    protected function side($type, $map = array(), $sideMethodMap = array(), $configMethodMap = array())
    {
        $side = $this->getSide();
        $config = $this->config($map, $configMethodMap);
        
        $sideMethodMap = array_merge($sideMethodMap, array(
            'type' => $type,
            'config' => $config
        ));
        
        foreach($sideMethodMap as $key => $value)
            $this->method($side, $key, $value, array());
        return $side;
    }
    
    protected function config($map, $methodMap = array())
    {
        $config = $this->getConfig();
        $config
            ->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($key) use($map){
                return $map[$key];
            }));
        
        foreach($map as $key => $value)
            $config->$key = $value;
        
        foreach($methodMap as $key => $value)
            $this->method($config, $key, 'value', array());
        return $config;
    }

    protected function getConditionGroup($logic = 'and', $negated = false, $conditions = array(5))
    {
        $group = $this->abstractMock('\PHPixie\ORM\Conditions\Condition\Group');
        $this->method($group, 'logic', $logic, array());
        $this->method($group, 'negated', $negated, array());
        $this->method($group, 'conditions', $conditions, array());
        return $group;
    }

    protected function getReusableResult()
    {
        return $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
    }
    
    protected function getReusableResultLoader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
    protected function getPlan()
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan\Step');
    }
    
    protected function getDatabaseQuery($type = 'select')
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\\'.ucfirst($type));
    }
    
    protected function getModel($methods = array())
    {
        return $this->abstractMock('\PHPixie\ORM\Model', $methods);
    }
    
    abstract protected function getHandler();
    abstract protected function getRelationship();
    abstract protected function getSide();
    abstract protected function getConfig();
}