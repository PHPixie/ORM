<?php

namespace PHPixie\ORM\Steps\Step\Query;

class Count extends \PHPixie\ORM\Steps\Step\Query
{
    protected $count;
    
    public function execute()
    {
        $this->count = $this->query->execute();

        if ($this->count === null)
            throw new \PHPixie\ORM\Exception\Plan("Query did not return a result.");
    }

    public function count()
    {
        if ($this->count === null)
            throw new \PHPixie\ORM\Exception\Plan("This plan step has not been executed yet.");

        return $this->count;
    }

}
