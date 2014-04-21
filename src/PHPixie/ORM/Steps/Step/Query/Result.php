<?php

namespace PHPixie\ORM\Steps\Step\Query;

abstract class Result extends \PHPixie\ORM\Steps\Step\Query implements \IteratorAggregate
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
            throw new \PHPixie\Exception\Step("This plan step has not been executed yet.")

        return $this->result;
    }

    public function getField($field)
    {
        foreach($this as $row)
            $values[] = $row->$field;

        return $values;
    }

    abstract public function getIterator();
}
