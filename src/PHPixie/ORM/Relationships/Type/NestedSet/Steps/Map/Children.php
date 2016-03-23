<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps\Map;

class Children
{
    protected $config;
    protected $query;
    protected $result;
    
    public function __construct($config, $query, $result)
    {
        $this->config = $config;
        $this->query  = $query;
        $this->result = $result;
    }
    
    public function execute()
    {
        $data = $this->result->getFields('left', 'right');
        foreach($data as $row) {
            $query
                ->startOrGroup()
                    ->where('left', '>', $row['left'])
                    ->where('right', '<', $row['right'])
                ->endOrGroup();
        }
    }
    
    public function usedConnections()
    {
        return array();
    }
}