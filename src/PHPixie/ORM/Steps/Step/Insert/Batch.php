<?php

namespace PHPixie\ORM\Steps\Step\Insert;

abstract class Batch extends \PHPixie\ORM\Steps\Step\Insert
{
    protected $queryPlanner;
    protected $fields;
    protected $data;
    
    public function __construct($queryPlanner, $insertQuery)
    {
        parent::__construct($insertQuery);
        $this->queryPlanner = $queryPlanner;
    }
    
    public function execute()
    {
        $this->prepareBatchData();
        var_dump($this->data);
        var_dump(1111);
        $this->queryPlanner->setBatchData($this->insertQuery, $this->fields, $this->data);
    }
    
    abstract protected function prepareBatchData();
}