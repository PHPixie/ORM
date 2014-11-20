<?php

namespace PHPixie\ORM\Steps\Step\Query;

abstract class Result extends \PHPixie\ORM\Steps\Step\Query 
                      implements \PHPixie\ORM\Steps\Result
{
    protected $result;
    
    public function execute()
    {
        $this->result = $this->query->execute();

        if ($this->result === null)
            throw new \PHPixie\ORM\Exception\Plan("Query did not return a result.");
    }

    public function result()
    {
        if ($this->result === null)
            throw new \PHPixie\ORM\Exception\Plan("This plan step has not been executed yet.");

        return $this->result;
    }

    public function getField($field, $skipNulls = true)
    {
        return $this->result->getField($field, $skipNulls);
    }
    
    public function getFields($fields)
    {
        return $this->result->getFields($fields);
    }

    abstract public function getIterator();
}
