<?php

class Group
{
    protected $logicPrecedance = array(
        'and' => 2,
        'xor' => 1,
        'or'  => 0
    );

    public function __construct($driver, $operatorParser)
    {
        $this->driver = $driver;
        $this->operatorParser = $operatorParser;
    }

    protected function expandGroup(& $group, $level = 0)
    {
        $res = array();

        $current = current($group);

        $relation = $current->relationship;

        if ($relation !== null) {
            if (!isset($res[$relation])) {
                $res[$relation] = array();
            }
            $res[$relation][]= $current;
        } else {
            $res[]= $current;
        }

        while (true) {
            if (($next = next($group)) === false)
                break;

            if ($this->logicPrecedance[$next->logic] < $level) {
                prev($group);
                break;
            }

            $right = $this->expandGroup($group, $this->logicPrecedance[$next->logic] + 1, $allSubqueries, $subqueries);
            $res = $this->merge($res, $right);

            $current = $next;
        }

        return $res;
    }

    protected function merge($left, $right)
    {
        if ($right instanceof \PHPixie\ORM\Conditions\Condition\Group) {
            $key = $right->relationship;
            if ($key === null)
                $key = 0;
            $right = array($key => $this->expandGroup($right->conditions()));
        }

        if (count($right) !== 1) {
            foreach($right as $condition)
                $left[] = $condition;
        } else {
            $key = key($right);
            $right = current($right);
            if ($key === 0) {
                $left[] = $right;
            } elseif (!isset($left[$key])) {
                $left[$key] = $right;
            } else {
                foreach($right as $condition)
                    $left[$key][] = $condition;
            }

        }

        return $left;
    }

    public function map($group, $loader)
    {
        $this->expandGroup($group);
    }

}
