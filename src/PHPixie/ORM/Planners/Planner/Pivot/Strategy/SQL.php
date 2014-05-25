<?php

namespace PHPixie\ORM\Planners\Planner\Pivot\Strategy;

class SQL extends \PHPixie\ORM\Planners\Planner\Pivot\Strategy
{
    protected $productAlias = 'insert_product';
    protected $idQueryAliasPrefix = 'id_query_';

    public function link($pivot, $firstSide, $secondSide, $plan)
    {
        $pivotTable = $pivot->pivot;

        $sides = array(
            'first' => $firstSide,
            'second' => $secondSide,
        );

        $sideData = array();
        foreach ($sides as $name => $side) {
            $alias = $this->idQueryAliasPrefix.$name;
            $sideData[] = array(
                'query' => $this->idQuery($side, $plan),
                'queryAlias' => $alias,
                'productIdField' => $alias.'.'.$side->repository->idField(),
                'productAlias' => $name,
                'productKey' => $this->productAlias.'.'.$name,
                'pivotKey' => $pivotTable.'.'.$side->pivotKey,
            );
        }

        $productQuery = $pivot->connection->query()
                                                ->fields(array(
                                                    $sides[0]['productAlias'] => $sides[0]['productIdField'],
                                                    $sides[1]['productAlias'] => $sides[1]['productIdField']
                                                ))
                                                ->table($sides[0]['query'], $sides[0]['queryAlias'])
                                                ->join($sides[1]['query'], $sides[1]['queryAlias'], 'cross');

        $filteredQuery = $pivot->connection->query('select')
                                                ->fields(array($sides[0]['productKey'], $sides[1]['productKey']))
                                                ->table($productQuery, $this->productAlias)
                                                ->join($pivotTable, null, 'left_outer')
                                                    ->on($sides[0]['productKey'], $sideData[0]['pivotKey'])
                                                    ->on($sides[1]['productKey'], $sideData[1]['pivotKey'])
                                                ->where($sideData[0]['pivotKey'], null);

        $insertQuery = $pivot->connection->query('insert')
                                                ->table($pivotTable)
                                                ->batchData(
                                                    array(
                                                        $sideData[0]['pivotKey'],
                                                        $sideData[1]['pivotKey']
                                                    ),
                                                    $filteredQuery
                                                );

        $queryStep = $this->steps->query($insertQuery);
        $plan->add($insertQuery);

    }
}
