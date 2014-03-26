<?php

namespace PHPixie\ORM\Query\Plan\Step\Cross;

class Insert extends \PHPixie\ORM\Query\Plan\Step\Query
{
    protected $keys;
    protected $resultSteps;

    public function __construct($query, $keys, $resultSteps)
    {
        parent::__construct($query);
        $this->keys = $keys;
        $this->resultSteps = $resultSteps;
    }

    public function execute()
    {
        $results = array();
        foreach ($this->resultSteps as $step) {
            $result = array();
            foreach($step->result() as $row)
                $result[] = array_values((array) $row);
            $results[] = $result;
        }
        $rows = $this->cartesian($results);
        $this->query->batchInsert($this->keys, $rows);
        parent::execute();
    }

    protected function cartesian($arrays)
    {
        $left = array_shift($arrays);
        $right = count($arrays) === 1 ? current($array) : $this->cartesian($arrays);
        $result = array();
        foreach($left as $l)
            foreach($right as $r)
                $result[] = array_merge($l, $r);

        return $result;
    }
}
