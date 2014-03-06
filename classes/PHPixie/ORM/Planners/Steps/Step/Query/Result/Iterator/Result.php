<?php

namespace PHPixie\ORM\Planners\Steps\Step\Query\Result\Iterators;

class Result impelements \Iterator
{
    abstract public function current();
    abstract public function key();
    abstract public function valid();
    abstract public function next();
    abstract public function rewind();

    public function getField($field)
    {
        $this->rewind();
        $values = array();
        foreach($this as $row)
            $values[] = $row->$field;

        return $values;
    }
}
