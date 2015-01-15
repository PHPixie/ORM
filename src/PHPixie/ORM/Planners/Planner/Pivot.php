<?php

namespace PHPixie\ORM\Planners\Planner;

class Pivot
{
    protected $planners;
    protected $steps;
    
    protected $sqlStrategy;
    protected $multiqueryStrategy;
    
    public function __construct($planners, $steps)
    {
        $this->planners = $planners;
        $this->steps    = $steps;
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
    {
    
    }
    
    protected function selectStrategy($pivot, $firstSide, $secondSide)
    {
        $pivotConnection = $pivot->connection();
        
        if (!($pivotConnection instanceof \PHPixie\Database\Type\SQL\Connection)) {
            return $this->multiqueryStrategy();
        }
        
        foreach(array($firstSide, $secondSide) as $side) {
            if ($side !== null && $side->connection() !== $pivotConnection) {
                return $this->multiqueryStrategy();
            }
        }

        return $this->sqlStrategy();
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

    protected function sqlStrategy()
    {
        if($this->sqlStrategy === null) {
            $this->sqlStrategy = $this->buildSqlStrategy(); 
        }
        
        return $this->sqlStrategy;
    }
    
    protected function multiqueryStrategy()
    {
        if($this->multiqueryStrategy === null) {
            $this->multiqueryStrategy = $this->buildMultiqueryStrategy(); 
        }
        
        return $this->multiqueryStrategy;
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
