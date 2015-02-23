<?php

namespace PHPixie\ORM\Planners\Planner\Pivot\Strategy;

class SQL extends \PHPixie\ORM\Planners\Planner\Pivot\Strategy
{
    protected $productAlias = 'insert_product';
    protected $idQueryAliasPrefix = 'id_query_';

    public function link($pivot, $firstSide, $secondSide, $plan)
    {
        $pivotTable = $pivot->source();

        $sides = array(
            'first' => $firstSide,
            'second' => $secondSide,
        );

        $sideData = array();
        foreach ($sides as $name => $side) {
            $idField = $side->repository()->config()->idField;
            $alias = $this->idQueryAliasPrefix.$name;
            $sideData[] = array(
                'query' => $this->idQuery($side, $plan),
                'queryAlias' => $alias,
                'productIdField' => $alias.'.'.$idField,
                'productAlias' => $name,
                'productKey' => $this->productAlias.'.'.$name,
                'pivotKey' => $side->pivotKey(),
                'fullPivotKey' => $pivotTable.'.'.$side->pivotKey(),
            );
        }

        $productQuery = $pivot->connection()->selectQuery()
                            ->fields(array(
                                $sideData[0]['productAlias'] => $sideData[0]['productIdField'],
                                $sideData[1]['productAlias'] => $sideData[1]['productIdField']
                            ))
                            ->table($sideData[0]['query'], $sideData[0]['queryAlias'])
                            ->join($sideData[1]['query'], $sideData[1]['queryAlias'], 'cross');

        $filteredQuery = $pivot->connection()->selectQuery()
                            ->fields(array($sideData[0]['productKey'], $sideData[1]['productKey']))
                            ->table($productQuery, $this->productAlias)
                            ->join($pivotTable, null, 'left_outer')
                                ->on($sideData[0]['productKey'], $sideData[0]['fullPivotKey'])
                                ->on($sideData[1]['productKey'], $sideData[1]['fullPivotKey'])
                            ->where($sideData[0]['fullPivotKey'], null);

        $insertQuery = $pivot->connection()->insertQuery()
                            ->table($pivotTable)
                            ->batchData(
                                array(
                                    $sideData[0]['pivotKey'],
                                    $sideData[1]['pivotKey']
                                ),
                                $filteredQuery
                            );

        $queryStep = $this->steps->query($insertQuery);
        $plan->add($queryStep);

    }
}
