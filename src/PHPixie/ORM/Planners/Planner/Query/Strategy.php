<?php

namespace PHPixie\ORM\Planners\Planner\Query;

abstract class Strategy
{
    abstract public function setSource($query, $source);
    abstract public function setBatchData($query, $fields, $data);
}
