<?php

namespace PHPixie\ORM\Steps\Step\Query\Insert;

class Batch extends \PHPixie\ORM\Steps\Step\Query\Insert
{
    protected $queryPlanner;
    protected $dataStep;
    
    public function __construct($queryPlanner, $insertQuery, $dataStep)
    {
        $this->queryPlanner = $queryPlanner;
        $this->dataStep = $dataStep;
        parent::__construct($insertQuery);
    }
    
    public function execute()
    {
        $data = $this->dataStep->data();
        
        if(!empty($data)) {
            $this->queryPlanner->setBatchData($this->query, $this->dataStep->fields(), $data);
            parent::execute();
        }
    }
}