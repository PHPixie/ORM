<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Pivot extends \PHPixie\ORM\Query\Plan\Planner
{
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
            
        return $this->strategy('subquery');
    }

    public function pivot($connection, $pivot)
    {
        return new Pivot\Pivot($connection, $pivot);
    }

    public function side($collection, $idField, $pivotKey)
    {
        return new Pivot\Side($collection, $idField, $pivotKey);
    }
    
    protected function buildStrategy($name)
    {
        $class = '\PHPixie\ORM\Planners\Planner\Pivot\Strategy\\'.$name;
        return new $class($this->steps);
    }
}
