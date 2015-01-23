<?php

namespace PHPixieTests\ORM\Planners\Planner\Pivot\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot\Strategy\SQL
 */
class SQLTest extends \PHPixieTests\ORM\Planners\Planner\Pivot\StrategyTest
{
    
    protected $productAlias = 'insert_product';
    protected $idQueryAliasPrefix = 'id_query_';
    
    protected function prepareLinkTest($pivot, $firstSide, $secondSide, $plan)
    {
        $sides = array(
            'first' => $firstSide,
            'second' => $secondSide,
        );

        $sideData = array();
        $key = 0;
        
        foreach ($sides as $name => $side) {
            $alias = $this->idQueryAliasPrefix.$name;
            $sideData[] = array(
                'query' => $this->prepareIdQuery($side, $plan, $key),
                'queryAlias' => $alias,
                'productIdField' => $alias.'.'.$side->repository->idField(),
                'productAlias' => $name,
                'productKey' => $this->productAlias.'.'.$name,
                'pivotKey' => $pivotTable.'.'.$side['pivotKey'],
            );
            
            $key++;
        }
        
        $productQuery = $this->getSQLQuery('select');
        $this->method($pivot['connection'], 'selectQuery', $productQuery, array(), 0);
        
        
        
    }
    
    protected function getConnection()
    {
        return $this->quickMock('\PHPixie\Database\Type\SQL\Connection');
    }
    
    protected function getSQLQuery($type)
    {
        return $this->quickMock('\PHPixie\Database\Type\SQL\Query\Type\\'.ucfirst($type));
    }
    
    protected function strategy()
    {
        return new \PHPixie\ORM\Planners\Planner\Pivot\Strategy\SQL(
            $this->planners,
            $this->steps
        );
    }
}