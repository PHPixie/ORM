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

    public function unlinkAll($pivot, $side, $plan)
    {}
    protected function selectStrategy($pivot, $firstSide, $secondSide)
    {
        $pivotConnection = $pivot->connection();
        if (!($pivotConnection instanceof \PHPixie\Database\Driver\PDO\Connection))
            return $this->strategies->pivot('multiquery');

        foreach(array($firstSide, $secondSide) as $side)
            if ($side !== null && $side->connection() !== $pivotConnection)
                return $this->strategies->pivot('multiquery');

        return $this->strategies->pivot('SQL');
    }

    public function pivot($connection, $source)
    {
        return new Pivot\Pivot($connection, $source);
    }
    
    public function pivotByConnectionName($connectionName, $source)
    {
        $connection = $this->database->connection($connectionName);
        return $this->pivot($connection, $source);
    }

    public function side($items, $repository, $pivotKey)
    {
        return new Pivot\Side($items, $repository, $pivotKey);
    }
}
