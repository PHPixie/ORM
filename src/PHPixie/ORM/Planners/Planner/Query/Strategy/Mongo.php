<?php

namespace PHPixie\ORM\Planners\Planner\Query\Strategy;

class Mongo extends \PHPixie\ORM\Planners\Planner\Query\Strategy
{
    public function setSource($query, $source)
    {
        $query->collection($source);
    }

    public function setBatchData($query, $fields, $data)
    {
        $items = array();
        foreach($data as $dataRow)
            $items[]= array_combine($fields, $dataRow);

        $query->batchData($items);
    }
}
