<?php

namespace PHPixieTests\ORM\Planners\Planner\Pivot\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot\Strategy\Multiquery
 */
class MultiqueryTest extends \PHPixieTests\ORM\Planners\Planner\Pivot\StrategyTest
{

    protected function prepareLinkTest($pivot, $firstSide, $secondSide, $plan)
    {
        $resultSteps = array();
        
        foreach(array($firstSide, $secondSide) as $key => $side) {
            $idQuery = $this->prepareIdQuery($side, $plan, $key*2);
            
            $resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Iterator');
            $resultSteps[]=$resultStep;
            
            $this->method($this->steps, 'iteratorResult', $resultStep, array($idQuery), $key);
            $this->method($plan, 'add', null, array($resultStep), $key*2+1);
        }
        
        $cartesianStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Pivot\Cartesian');
        $this->method($this->steps, 'pivotCartesian', $cartesianStep, array($resultSteps), 2);
        $this->method($plan, 'add', null, array($cartesianStep), 4);
        
        $insertStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Pivot\Insert');
        $this->method($this->steps, 'pivotInsert', $insertStep, array(
            $pivot['connection'],
            $pivot['source'],
            array(
                $firstSide['pivotKey'],
                $secondSide['pivotKey']
            ),
            $cartesianStep
        ), 3);
        
        $this->method($plan, 'add', null, array($insertStep), 5);
    }
    
    protected function getConnection()
    {
        return $this->quickMock('\PHPixie\Database\Connection');
    }
    
    protected function strategy()
    {
        return new \PHPixie\ORM\Planners\Planner\Pivot\Strategy\Multiquery(
            $this->planners,
            $this->steps
        );
    }
}