<?php

namespace PHPixie\ORM;

class Relationships
{
    protected $ormBuilder;
    protected $relationships = array();
    protected $embedsGroupMapper;

    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    public function get($name)
    {
        if (!array_key_exists($name, $this->relationships)) {
            $this->relationships[$name] = $this->buildRelationship($name);
        }

        return $this->relationships[$name];
    }

    protected function buildRelationship($name)
    {
        $class = '\PHPixie\ORM\Relationships\Type\\'.ucfirst($name);
        return new $class($this->ormBuilder);
    }
    
    public function map(){}
}
