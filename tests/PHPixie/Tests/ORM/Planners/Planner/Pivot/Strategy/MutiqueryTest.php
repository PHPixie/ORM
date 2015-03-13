<?php

namespace PHPixie\Tests\ORM\Planners\Planner\Pivot\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot\Strategy\Multiquery
 */
class MultiqueryTest extends \PHPixie\Tests\ORM\Planners\Planner\Pivot\StrategyTest
{

    protected function prepareLinkTest($pivot, $firstSide, $secondSide, $plan)
    {
        $resultFilters = array();
        
        $planAt = 0;
        $stepsAt = 0;
        
        foreach(array($firstSide, $secondSide) as $key => $side) {
            $idQuery = $this->prepareIdQuery($side, $plan, $planAt++);
            
            $resultStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Result');
            $this->method($this->steps, 'iteratorResult', $resultStep, array($idQuery), $stepsAt++);
            
            $resultFilter = $this->abstractMock('\PHPixie\ORM\Steps\Result\Filter');
            $id = 'id'.$key;
            $this->prepareModelConfig($side['repository'], array('idField' => $id), 2);
            $this->method($this->steps, 'resultFilter', $resultFilter, array($resultStep, array($id)), $stepsAt++);
            
            $resultFilters[]=$resultFilter;
            
            $this->method($plan, 'add', null, array($resultStep), $planAt++);
        }
        
        $cartesianStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Pivot\Cartesian');
        $this->method($this->steps, 'pivotCartesian', $cartesianStep, array(
            array(
                $firstSide['pivotKey'],
                $secondSide['pivotKey']
            ),
            $resultFilters
        ), $stepsAt++);
        $this->method($plan, 'add', null, array($cartesianStep), $planAt++);
        
        $selectQuery = $this->abstractMock('\PHPixie\Database\Query\Type\Select');
        $this->method($pivot['pivot'], 'databaseSelectQuery', $selectQuery, array(), 0);
        
        $uniqueDataStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data\Unique');
        $this->method($this->steps, 'uniqueDataInsert', $uniqueDataStep, array(
            $cartesianStep,
            $selectQuery
        ), $stepsAt++);
        $this->method($plan, 'add', null, array($uniqueDataStep), $planAt++);
        
        $insertQuery = $this->abstractMock('\PHPixie\Database\Query\Type\Insert');
        $this->method($pivot['pivot'], 'databaseInsertQuery', $insertQuery, array(), 1);
        
        $insertStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Insert\Batch');
        $this->method($this->steps, 'batchInsert', $insertStep, array(
            $insertQuery,
            $uniqueDataStep
        ), $stepsAt++);
        $this->method($plan, 'add', null, array($insertStep), $planAt++);
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