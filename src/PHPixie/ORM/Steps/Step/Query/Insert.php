<?php

namespace PHPixie\ORM\Steps\Step\Query;

class Insert extends \PHPixie\ORM\Steps\Step\Query
{
    protected $fields;
    protected $insertDataStep;
    
    public function __construct($insertQuery, $insertDataStep)
    {
        parent::__construct($insertQuery);
        $this->insertDataStep = $insertDataStep;
    }
    
    public function execute()
    {
        $this->queryPlanner->setBatchData(
                    $this->insertQuery,
                    $this->insertDataStep->fields(),
                    $this->insertDataStep->data()
                );
        parent::execute();
    }
}