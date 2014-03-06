<?php

namespace \PHPixie\ORM\Planners;

use \PHPixie\ORM\Planners\Steps\Step;

class Steps
{
    public function query($query)
    {
        return new Step\Query($query);
    }

    public function result($query)
    {
        return new Step\Query\Result\Single($query);
    }

    public function reusableResult($query)
    {
        return new Step\Query\Result\Reusable($query);
    }

    public function inSubquery($query, $placeholder, $logic, $negated, $field)
    {
        return new Step\InSubquery($query, $placeholder, $logic, $negated, $field);
    }

    public function crossInsert($query, $leftKey, $rightKey)
    {
        return new Step\Cross\Insert($query, $leftKey, $rightKey);
    }

    public function crossDelete($query, $leftKey, $rightKey)
    {
        return new Step\Cross\Delete($query, $leftKey, $rightKey);
    }

    public function push($updateQuery, $path)
    {
        return new Step\Push($updateQuery, $path);
    }

    public function pull($updateQuery, $path, $idField)
    {
        return new Step\Pull($updateQuery, $path, $idField);
    }

}
