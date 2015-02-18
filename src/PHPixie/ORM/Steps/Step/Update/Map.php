<?php

namespace PHPixie\ORM\Steps\Step\Update;

class Map extends \PHPixie\ORM\Steps\Step\Update
{
    protected $map;
    protected $resultStep;

    public function __construct($updateQuery, $map, $resultStep)
    {
        parent::__construct($updateQuery);
        $this->map = $map;
        $this->resultStep = $resultStep;
    }
    
    public function execute()
    {
        $fieldRows = $this->resultStep->getFields(array_values($this->map));
        if(count($fieldRows) !== 1)
            throw new \PHPixie\ORM\Exception\Plan("Result used as update source must contain a single item.");
        $fields = current($fieldRows);
        $set = array();
        foreach($this->map as $target => $source) {
            $set[$target] = $fields[$source];
        }
        $this->updateQuery->set($set);
    }

}
