<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler;

class Pivot
{
    public function idsQuery($conditions, $side, $opposingSide, $opposingHandler, $config, $plan)
    {
        $subquery = $opposingHandler->idsQuery($conditions, $config, $plan);

        $query = $repo->connection()->query('select')
                                        ->fields(array($config["pivot_{$side}_key"]));

        $this->setCollection($query, $config["pivot"]);
        $this
            ->pivotStrategy($config)
            ->addCondition($query, 'and', false, $config["pivot_{$opposingSide}_key"], $subquery);

        return $query;
    }

    protected function setCollection($query, $pivot)
    {
        $this->adapter->setCollection($query, $pivot);
    }

    protected function pivotStrategy($side, $config)
    {
        return $this->adapter->pivotStrategy($side, $config);
    }

}
