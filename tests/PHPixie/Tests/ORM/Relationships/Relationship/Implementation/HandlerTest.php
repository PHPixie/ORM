<?php

namespace PHPixie\Tests\ORM\Relationships\Relationship\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
 */
abstract class HandlerTest extends \PHPixie\Test\Testcase
{
    protected $handler;
    protected $models;
    protected $planners;
    protected $plans;
    protected $steps;
    protected $loaders;
    protected $mappers;
    protected $relationship;

    protected $modelMocks = array();
    protected $mapperMocks = array();

    public function setUp()
    {
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->plans = $this->quickMock('\PHPixie\ORM\Plans');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        $this->relationship = $this->getRelationship();
        $this->mappers = $this->quickMock('\PHPixie\ORM\Mappers');

        $models = array(
            'database' => '\PHPixie\ORM\Models\Type\Database',
            'embedded' => '\PHPixie\ORM\Models\Type\Embedded',
        );

        foreach($models as $key => $class) {
            $this->modelMocks[$key] = $this->quickMock($class);
            $this->method($this->models, $key, $this->modelMocks[$key], array());
        }

        $mappers = array(
            'conditions' => '\PHPixie\ORM\Mappers\Conditions',
            'preload' => '\PHPixie\ORM\Mappers\Preload',
            'cascadeDelete' => '\PHPixie\ORM\Mappers\Cascade\Mapper\Delete',
        );

        foreach($mappers as $key => $class) {
            $this->mapperMocks[$key] = $this->quickMock($class);
            $this->method($this->mappers, $key, $this->mapperMocks[$key], array());
        }

        $this->handler = $this->getHandler();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Implementation\Handler::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    protected function setRepositories($repositories)
    {
        $this->modelMocks['database']
                    ->expects($this->any())
                    ->method('repository')
                    ->will($this->returnCallback(function($name) use($repositories){
                        return $repositories[$name];
                    }));
    }

    protected function prepareRepositoryConfig($repository, $params = array(), $at = 0)
    {
        $config = $this->getModelConfig();
        $this->method($repository, 'config', $config, array(), $at);

        foreach($params as $key => $value) {
            $config->$key = $value;
        }
        
        return $config;
    }

    protected function getModelConfig()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
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
        
        if(method_exists($config, 'get')) {
            $config
                ->expects($this->any())
                ->method('get')
                ->will($this->returnCallback(function($key) use($map){
                    return $map[$key];
                }));
        }
        
        foreach($map as $key => $value)
            $config->$key = $value;

        foreach($methodMap as $key => $value)
            $this->method($config, $key, $value, array());
        return $config;
    }

    protected function getCollectionCondition($logic = 'and', $negated = false, $conditions = array(5))
    {
        $group = $this->abstractMock('\PHPixie\ORM\Conditions\Condition\Collection');
        $this->method($group, 'logic', $logic, array());
        $this->method($group, 'isNegated', $negated, array());
        $this->method($group, 'conditions', $conditions, array());
        return $group;
    }


    protected function preloadPropertyValue($preloadAt = 0)
    {
        $property = $this->getPreloadCascadingProperty();
        $preload = $this->getPreloadValue();

        $this->method($property, 'preload', $preload, array(), $preloadAt);

        return array(
            'property' => $property,
            'preload'  => $preload,
        );
    }

    protected function getPreloadCascadingProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Values\Preload\Property\Cascading');
    }

    protected function getPreloadValue()
    {
        return $this->quickMock('\PHPixie\ORM\Values\Preload');
    }
    
    protected function getCascadePath()
    {
        return $this->quickMock('\PHPixie\ORM\Mappers\Cascade\Path');
    }

    protected function getReusableResult()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Result\Reusable');
    }

    protected function getReusableResultStep()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
    }
    
    protected function getQueryStep()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Step\Query');
    }

    protected function getReusableResultLoader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }

    protected function getLoader()
    {
        return $this->abstractMock('\PHPixie\ORM\Loaders\Loader');
    }

    protected function getLoaderProxy($type)
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\\'.ucfirst($type));
    }

    protected function getPlan()
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan\Steps');
    }

    protected function getDatabaseQuery($type = 'select')
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\\'.ucfirst($type));
    }

    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Model\Entity');
    }

    protected function getPlanners($types)
    {
        $planners = array();
        foreach($types as $type) {
            $planners[$type] = $this->getPlanner($type);
        }
        return $planners;
    }

    protected function getPlanner($type)
    {
        return $this->quickMock('\PHPixie\ORM\Planners\Planner\\'.ucfirst($type));
    }

    abstract protected function getHandler();
    abstract protected function getRelationship();
    abstract protected function getSide();
    abstract protected function getConfig();
}
