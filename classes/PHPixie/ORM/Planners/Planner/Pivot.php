<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Pivot extends \PHPixie\ORM\Query\Plan\Planner
{
    public function link($pivot, $firstSide, $secondSide, $plan)
    {
        $linkMethod = $this->linkMethod($pivot, $firstSide, $secondSide);
        $this->$linkMethod($pivot, $firstSide, $secondSide, $plan);
    }

    public function unlink($pivot, $firstSide, $plan, $secondSide = null)
    {
        $deleteQuery = $pivot->query('delete');
        $sides = array($firstSide);
        if ($secondSide !== null)
            $sides[] = $secondSide;
        foreach($sides as $side)
            $this->planners->in()->->collection(
                                                    $deleteQuery,
                                                    $side->pivotKey(),
                                                    $side->collection(),
                                                    $side->idField(),
                                                    $plan,
                                                    'and',
                                                    false
                                                );
        $step = $this->steps->query($deleteQuery);
        $plan->push($step);
    }

    protected function linkMethod($pivot, $firstSide, $secondSide)
    {
        $pConn = $pivot->connection();
        $fConn = $firstSide-> repository->connection();
        $sConn = $secondSide-> repository->connection();

        if ($pConn === $fConn && $fConn === $sConn && $pConn instanceof PHPixie\DB\Driver\PDO\Connection)
            return 'link_pdo';
        return 'link_generic';
    }

    protected function linkPdo($pivot, $firstSide, $secondSide, $plan)
    {
        $firstQuery = $this->idQuery($firstSide, $plan);
        $secondQuery = $this->idQuery($firstSide, $plan);

        $crossQuery = $pivot->connection()->query('select')
                                                ->fields(array(
                                                    'first_side.'$sides[0]['id_field'],
                                                    'second_side.'$sides[1]['id_field']
                                                ))
                                                ->table($queries[0], 'first_side')
                                                ->join($queries[1], 'second_side', 'cross');
        $insertQuery = $pivot->query('insert')
                                    ->onDuplicateKey('update')
                                    ->batchData(array(
                                        $firstSide->pivotKey(),
                                        $secondSide->pivotKey()
                                    ), $crossQuery);
        $step = $this->steps->query($insertQuery);
        $plan->push($step);
    }

    protected function linkGeneric($pivot, $firstSide, $secondSide, $plan)
    {
        $firstQuery = $this->idQuery($firstSide, $plan);
        $secondQuery = $this->idQuery($firstSide, $plan);

        $firstStep = $this->steps->result($firstQuery);
        $secondStep = $this->steps->result($secondQuery);

        $insertQuery = $pivot->query('insert')
                            ->onDuplicateKey('update');
        $keys = array(
            $firstSide->pivotKey(),
            $secondSide->pivotKey()
        );

        $step = $this->steps->pivotInsert($insertQuery, $keys, array($firstStep, $secondStep));
        $plan->push($step);

    }

    protected function idQuery($side)
    {
        $repository = $side->repository();
        $idField = $repository()->idField();
        $query = $repository->dbQuery()->fields(array($idField));
        $this->planners->in()->collection($query, $idField, $collection, $idField, $plan, 'and', false);

        return $query;
    }

    public function pivot($connection, $pivot)
    {
        return new Pivot\Pivot($connection, $pivot);
    }

    public function side($collection, $idField, $pivotKey)
    {
        return new Pivot\Side($collection, $idField, $pivotKey);
    }
}
