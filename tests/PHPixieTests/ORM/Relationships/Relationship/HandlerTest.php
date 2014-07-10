<?php

namespace PHPixieTests\ORM\Relationships\Relationship;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Handler
 */
abstract class HandlerTest extends \PHPixieTests\AbstractORMTest
{
    protected $handler;
    protected $ormBuilder;
    protected $relationship;
    protected $repositories;
    protected $planners;
    protected $steps;
    protected $loaders;
    protected $groupMapper;
    protected $cascadeMapper;
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->relationship = $this->getRelationship();
        $this->repositories = $this->quickMock('\PHPixie\ORM\Repositories');
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
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
        foreach($methodMap as $key => $value)
            $this->method($config, $key, 'value', array());
        return $config;
    }
    
    protected function getModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Model');
    }
    
    abstract protected function getHandler();
    abstract protected function getRelationship();
    abstract protected function getSide();
    abstract protected function getConfig();
}