<?php

namespace PHPixie\ORM\Planners\Planner;

class Pivot extends \PHPixie\ORM\Planners\Planner
{
    protected $strategies;
    
    public function __construct($strategies)
    {
        $this->strategies = $strategies;    
    }
    
    public function link($pivot, $firstSide, $secondSide, $plan)
    {
       $strategy = $this->selectStrategy($pivot, $firstSide, $secondSide);
       $strategy->link($pivot, $firstSide, $secondSide, $plan);
    }

    public function unlink($pivot, $firstSide, $secondSide, $plan)
    {
       $strategy = $this->selectStrategy($pivot, $firstSide, $secondSide);
       $strategy->unlink($pivot, $firstSide, $secondSide, $plan);
    }

    protected function selectStrategy($pivot, $firstSide, $secondSide)
    {
        $pivotConnection = $pivot->connection();
        if (!($pivotConnection instanceof \PHPixie\DB\Driver\PDO\Connection))
            return $this->strategy('multiquery');

        foreach(array($firstSide, $secondSide) as $side)
            if ($side !== null && $side->repository->connection() !== $pivotConnection)
                return $this->strategy('multiquery');

        return $this->strategy('SQL');
    }

    public function pivot($connection, $source)
    {
        return new Pivot\Pivot($connection, $source);
    }

    public function side($items, $repository, $pivotKey)
    {
        return new Pivot\Side($items, $repository, $pivotKey);
    }
}
