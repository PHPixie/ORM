<?php

namespace PHPixie\ORM\Steps\Step\Query;

abstract class Result extends \PHPixie\ORM\Steps\Step\Query implements \IteratorAggregate
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

    public function getField($field)
    {
        $values = array();
        foreach($this as $row)
            if(property_exists($row, $field))
                $values[] = $row->$field;
        return $values;
    }

    abstract public function getIterator();
}
