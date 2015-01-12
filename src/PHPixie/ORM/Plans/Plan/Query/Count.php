<?php

namespace PHPixie\ORM\Plans\Plan\Query;

class Count extends \PHPixie\ORM\Plans\Plan\Query
{
    public function __construct($plans, $countStep)
    {
        parent::__construct($plans, $countStep);
    }
    
    public function execute()
    {
        parent::execute();
        return $this->queryStep->count();
    }
}
