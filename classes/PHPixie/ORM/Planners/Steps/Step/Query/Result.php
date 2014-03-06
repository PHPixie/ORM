<?php

namespace PHPixie\ORM\Query\Plan\Step\Query;

abstract class Result extends \PHPixie\ORM\Query\Plan\Step\Query
{
    protected $result;
    protected function execute()
    {
        $this->result = $this->query->execute();

        if ($this->result === null)
            throw new \PHPixie\Exception\Step("Query did not return a result.")
    }

    protected function result()
    {
        if ($this->result === null)
            throw new \PHPixie\Exception\Step("This query step has not been executed yet.")

        return $this->result;
    }

    abstract public function iterator();
}
