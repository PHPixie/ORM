<?php

namespace PHPixie\Tests\ORM\Planners\Planner\Pivot;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot\Strategy
 */
abstract class StrategyTest extends \PHPixie\Test\Testcase
{
    protected $planners;
    protected $steps;
    
    protected $strategy;
    
    public function setUp()
    {
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        
        $this->strategy = $this->strategy();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::link
     * @covers ::<protected>
     */
    public function testLink()
    {
        $firstSide = $this->side(true);
        $secondSide =$this->side(false);
        
        $pivot = $this->pivot();
        $plan = $this->getPlan();
        
        $this->prepareLinkTest($pivot, $firstSide, $secondSide, $plan);
        $this->strategy->link($pivot['pivot'], $firstSide['side'], $secondSide['side'], $plan);
    }
    
    protected function prepareIdQuery($side, $plan, $planAt = 0, $repositoryAt = 0)
    {
        $query = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
        
        $this->method($side['repository'], 'query', $query, array(), $repositoryAt++);
        $this->prepareModelConfig($side['repository'], array('idField' => 'id'), $repositoryAt++);
        
        $this->method($query, 'in', $query, array($side['items']), 0);
        
        $queryPlan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Query\Loader');
        $requiredPlan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
        $resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        $databaseQuery = $this->quickMock('\PHPixie\Database\Query\Type\Select');
        
        $this->method($query, 'planFind', $queryPlan, array(), 1);
        $this->method($queryPlan, 'requiredPlan', $requiredPlan, array(), 0);
        $this->method($queryPlan, 'queryStep', $resultStep, array(), 1);
        $this->method($resultStep, 'query', $databaseQuery, array(), 0);
        
        $this->method($plan, 'appendPlan', null, array($requiredPlan), $planAt);
        $this->method($databaseQuery, 'fields', null, array(array('id')), 0);
        
        return $databaseQuery;
    }
    
    protected function pivot()
    {
        $pivot = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Pivot');
        
        $methods = array(
            'connection' => $this->getConnection(),
            'source' => 'pixies_fairies',
        );
        
        foreach($methods as $method => $value) {
            $this->method($pivot, $method, $methods[$method], array());
        }
        
        $methods['pivot'] = $pivot;
        
        return $methods;
    }
    
    protected function side($first = true)
    {
        $source = $first ? 'pixie' : 'fairy';
        
        $side = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Side');
        
        $methods = array(
            'items' => array($this->quickMock('\PHPixie\ORM\Models\Type\Database\Query')),
            'repository' => $this->quickMock('\PHPixie\ORM\Models\Type\Database\Repository'),
            'pivotKey' => $source.'Id',
        );
        
        foreach($methods as $method => $value) {
            $this->method($side, $method, $methods[$method], array());
        }
        
        $methods['side'] = $side;
        
        return $methods;
    }
    
    protected function prepareModelConfig($repository, $properties, $repositoryAt = 0)
    {
        $config = $this->getConfig();
        foreach($properties as $key => $value) {
            $config->$key = $value;
        }
        
        $this->method($repository, 'config', $config, array(), $repositoryAt);
    }
    
    protected function getPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    protected function getConfig()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
    }
    
    abstract protected function getConnection();
    abstract protected function strategy();
}
