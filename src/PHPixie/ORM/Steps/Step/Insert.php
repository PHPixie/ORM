<?php

namespace PHPixie\ORM\Steps\Step;

abstract class Insert extends \PHPixie\ORM\Steps\Step
{
    protected $insertQuery;
    
    public function __construct($insertQuery)
    {
        $this->insertQuery = $insertQuery;
    }
}