<?php

namespace PHPixie\ORM\Plans\Plan\Query;

class Count extends \PHPixie\ORM\Plans\Plan\Query
{
    public function __construct($transaction, $requiredPlan, $queryStep)
    {
        parent::__construct($transaction, $requiredPlan, $queryStep);
    }
    
    public function execute()
    {
        parent::execute();
        return $this->queryStep->count();
    }
}
