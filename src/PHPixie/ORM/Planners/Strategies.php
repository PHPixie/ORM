<?php

namespace PHPixie\ORM\Planners;

class Strategies
{
    public function query($type)
    {
        $class = '\PHPixie\ORM\Planners\Planner\Query\Strategy\\'.$type;
        return new $class;
    }
}