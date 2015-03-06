<?php

namespace PHPixie\ORM\Planners\Planner;

class Pivot extends \PHPixie\ORM\Planners\Planner
{
    protected $planners;
    protected $steps;
    protected $database;
    
    public function __construct($planners, $steps, $database)
    {
        $this->planners = $planners;
        $this->steps    = $steps;
        $this->database = $database;
    }
    
    public function link($pivot, $firstSide, $secondSide, $plan)
    {
       $strategy = $this->selectStrategy($pivot, $firstSide, $secondSide);
       $strategy->link($pivot, $firstSide, $secondSide, $plan);
    }

    public function unlink($pivot, $firstSide, $secondSide, $plan)
    {
        $this->unlinkSides($pivot, $firstSide, $plan, $secondSide);
    }

    public function unlinkAll($pivot, $side, $plan)
    {
        $this->unlinkSides($pivot, $side, $plan);
    }
    
    protected function unlinkSides($pivot, $firstSide, $plan, $secondSide = null)
    {
        $sides = array($firstSide);
        
        if($secondSide !== null) {
            $sides[]= $secondSide;
        }
        
        $deleteQuery = $pivot->databaseDeleteQuery();

        foreach ($sides as $side) {
            $this->planners->in()->itemIds(
                $deleteQuery,
                $side->pivotKey(),
                $side->repository(),
                $side->items(),
                $plan
            );
        }

        $deleteStep = $this->steps->query($deleteQuery);
        $plan->add($deleteStep);
    }
    
    protected function selectStrategy($pivot, $firstSide, $secondSide)
    {
        $pivotConnection = $pivot->connection();
        
        if (!($pivotConnection instanceof \PHPixie\Database\Type\SQL\Connection)) {
            return $this->strategy('multiquery');
        }
        
        foreach(array($firstSide, $secondSide) as $side) {
            if ($side !== null && $side->connection() !== $pivotConnection) {
                return $this->strategy('multiquery');
            }
        }

        return $this->strategy('sql');
    }

    public function pivot($connection, $source)
    {
        return new Pivot\Pivot($this->planners->query(), $connection, $source);
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

    protected function buildSqlStrategy()
    {
        return new \PHPixie\ORM\Planners\Planner\Pivot\Strategy\SQL(
            $this->planners,
            $this->steps
        );
    }
    
    protected function buildMultiqueryStrategy()
    {
        return new \PHPixie\ORM\Planners\Planner\Pivot\Strategy\Multiquery(
            $this->planners,
            $this->steps
        );
    }

}
