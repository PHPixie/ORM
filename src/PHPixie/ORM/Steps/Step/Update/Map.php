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
        $items = $this->resultStep->result()->asArray();
        if(count($items) !== 1)
            throw new \PHPixie\ORM\Exception\Plan("Result used as update source must contain a single item.");
        
        $item = current($items);
        $set = array();
        foreach($this->map as $target => $source) {
            $set[$target] = array_key_exists($source, $item) ? $item[$source] : null;
        }
        
        $this->updateQuery->set($set);
    }

}
