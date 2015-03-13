<?php

namespace PHPixie\Tests\ORM\Planners\Planner\Pivot\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot\Strategy\SQL
 */
class SQLTest extends \PHPixie\Tests\ORM\Planners\Planner\Pivot\StrategyTest
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
            
            $config = $this->getConfig();
            $config->idField = 'id';
            $this->method($side['repository'], 'config', $config, array(), 0);
            
            $sideData[] = array(
                'query'          => $this->prepareIdQuery($side, $plan, $key, 1),
                'queryAlias'     => $alias,
                'productIdField' => $alias.'.'.$config->idField,
                'productAlias'   => $name,
                'productKey'     => $this->productAlias.'.'.$name,
                'pivotKey'       => $side['pivotKey'],
                'fullPivotKey'   => $pivot['source'].'.'.$side['pivotKey'],
            );
            
            $key++;
        }
        
        $productQuery = $this->getSQLQuery('select');
        $this->method($pivot['connection'], 'selectQuery', $productQuery, array(), 0);
        $this->prepareChainQuery($productQuery, array(
            array('fields', array(
                array(
                    $sideData[0]['productAlias'] => $sideData[0]['productIdField'],
                    $sideData[1]['productAlias'] => $sideData[1]['productIdField']
                )
            )),
            array('table', array(
                $sideData[0]['query'],
                $sideData[0]['queryAlias']
            )),
            array('join', array(
                $sideData[1]['query'],
                $sideData[1]['queryAlias'],
                'cross'
            ))
        ));
        
        $filteredQuery = $this->getSQLQuery('select');
        $this->method($pivot['connection'], 'selectQuery', $filteredQuery, array(), 1);
        $this->prepareChainQuery($filteredQuery, array(
            array('fields', array(
                array(
                    $sideData[0]['productKey'],
                    $sideData[1]['productKey']
                )
            )),
            array('table', array(
                $productQuery,
                $this->productAlias
            )),
            array('join', array(
                $pivot['source'],
                null,
                'left_outer'
            )),
            array('on', array(
                $sideData[0]['productKey'],
                $sideData[0]['fullPivotKey']
            )),
            array('on', array(
                $sideData[1]['productKey'],
                $sideData[1]['fullPivotKey']
            )),
            array('where', array(
                $sideData[0]['fullPivotKey'],
                null
            )),
        ));
        
        $insertQuery = $this->getSQLQuery('insert');
        $this->method($pivot['connection'], 'insertQuery', $insertQuery, array(), 2);
        $this->prepareChainQuery($insertQuery, array(
            array('table', array(
                $pivot['source']
            )),
            array('batchData', array(
                array(
                    $sideData[0]['pivotKey'],
                    $sideData[1]['pivotKey']
                ),
                $filteredQuery
            )),
        ));
        
        $queryStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query');
        $this->method($this->steps, 'query', $queryStep, array($insertQuery), 0);
        $this->method($plan, 'add', null, array($queryStep), 2);
    }
    
    protected function prepareChainQuery($query, $sets)
    {
        $at = 0;
        foreach($sets as $set) {
            $this->method($query, $set[0], $query, $set[1], $at);
            $at++;
        }
    }
    
    protected function getConnection()
    {
        return $this->abstractMock('\PHPixie\Database\Type\SQL\Connection');
    }
    
    protected function getSQLQuery($type)
    {
        return $this->abstractMock('\PHPixie\Database\Type\SQL\Query\Type\\'.ucfirst($type));
    }
    
    protected function strategy()
    {
        return new \PHPixie\ORM\Planners\Planner\Pivot\Strategy\SQL(
            $this->planners,
            $this->steps
        );
    }
}