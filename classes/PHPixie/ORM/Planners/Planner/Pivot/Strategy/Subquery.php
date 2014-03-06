<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy;

class Subquery extends \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy
{
    public function linkPlan($leftCollection, $rightCollection, $leftRepository, $rightRepository, $leftPivotKey, $rightPivotKey, $plan)
    {
        $leftQuery = $this->collectionQuery($leftCollection, $leftRepository, $leftPivotKey, $plan);
        $rightQuery = $this->collectionQuery($rightCollection, $rightRepository, $rightPivotKey, $plan);

        $query = $this->db->query('insert', $pivotConnection)
                                                ->table($pivotTable)
                                                ->batchInsert(
                                                    array($leftKey, $rightKey),
                                                    $leftQuery->join($rightQuery, 'cross')
                                                );

        $plan->push($this->steps->query($query));

        return $plan;
    }

    public function unlinkPlan($leftCollection, $rightCollection, $leftRepository, $rightRepository, $leftPivotKey, $rightPivotKey, $plan)
    {
        $leftQuery = $this->collectionQuery($leftCollection, $leftRepository, $leftPivotKey, $plan);
        $rightQuery = $this->collectionQuery($rightCollection, $rightRepository, $rightPivotKey, $plan);

        $query = $this->db->query('delete', $pivotConnection)
                                                ->table($pivotTable)
                                                ->where($leftKey, 'in', $leftQuery)
                                                ->where($rightKey, 'in', $rightQuery)

        $plan->push($this->steps->query($query));

        return $plan;
    }

    protected function collectionQuery($collection, $repository, $pivotKey, $plan)
    {
        $idField = $repository->idField();
        $query = $collection()->query('select')
                                                ->fields(array($idField));
        $innerQuery = null;
        foreach($collection->getIds($idField, true) as $id)
            $innerQuery = $this->union(
                                    $innerQuery,
                                    $this->idQuery($repository, $idField, $id)
                                );

        foreach($collection->queries() as $ormQuery)
            $innerQuery = $this->union(
                                    $innerQuery,
                                    $this->mapOrmQuery($ormQuery, $plan, $idField)
                                );

        if ($innerQuery === null)
            throw new \PHPixie\Exception\Mapper("No ids or queries set for pivot '{$pivotKey}' field.");

        $query->table($innerQuery, "cross_{$pivotKey}");

        return $query;
    }

    protected function union($query, $subquery)
    {
        if ($query === null)
            return $subquery;
        return $query->union($subquery);
    }

    protected function idQuery($repository, $idField, $id)
    {
        return $repository->collection()->query('select')
                                                    ->fields(array(
                                                        $idField => $this->db->expr($id)
                                                    ));
    }

    protected function mapOrmQuery($ormQuery, $plan, $idField)
    {
        $subplan = $ormQuery->map();
        $subquery = $subplan->popResultQuery();
        $plan->prependPlan($subplan);
        $subquery->fields(array($idField));

        return $subquery;
    }
}
