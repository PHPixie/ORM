<?php

namespace PHPixie\ORM\Steps\Step;

abstract class Update extends \PHPixie\ORM\Steps\Step
{
    protected $updateQuery;
    
    public function __construct($updateQuery)
    {
        $this->updateQuery = $updateQuery;
    }
}